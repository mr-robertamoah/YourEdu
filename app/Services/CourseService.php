<?php

namespace App\Services;

use App\DTOs\AttachmentDTO;
use App\DTOs\CourseDTO;
use App\Events\DeleteCourseEvent;
use App\Events\NewCourseEvent;
use App\Events\UpdateCourseEvent;
use App\Exceptions\AccountNotFoundException;
use App\Exceptions\CourseException;
use App\Http\Resources\CourseResource;
use App\Http\Resources\DashboardCourseResource;
use App\Http\Resources\UserAccountResource;
use App\Notifications\CourseCreatedNotification;
use App\Traits\AliasesServiceTrait;
use App\Traits\DashboardItemServiceTrait;
use App\User;
use App\YourEdu\Admin;
use App\YourEdu\Course;
use App\YourEdu\PostAttachment;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class CourseService
{
    use DashboardItemServiceTrait,
        AliasesServiceTrait;

    public function createCourseAsAttachment(CourseDTO $courseDTO)
    {
        $this->checkAttachedbyAccount($courseDTO);

        $courseDTO->isAttachment = true;

        $course = $this->createCourse($courseDTO);

        $courseDTO = $courseDTO->withAddedby($course->addedby);

        $course = $courseDTO->withAddedby($course->addedby);

        $this->createAliases($courseDTO->withAliasable($course));

        return $course->refresh();
    }

    public function createCourseAttachmentAlias(CourseDTO $courseDTO)
    {
        $course = $this->getModel('course', $courseDTO->courseId);

        $courseDTO = $courseDTO->withAddedby(
            $this->getModel($courseDTO->account, $courseDTO->accountId)
        );

        $alias = $this->createAlias(
            $courseDTO
                ->withAliasable($course)
                ->setAlias()
        );

        if (is_not_null($alias)) {
            return $course->refresh();
        }

        $this->throwCourseException(
            message: 'alias was not created',
            data: $courseDTO
        );
    }

    public function deleteCourseAsAttachment(CourseDTO $courseDTO)
    {
        $course = $this->getModel('course', $courseDTO->courseId);

        $this->checkAttachmentAuthorization($course, $courseDTO);

        $deletionStatus = $course->delete();

        if ($deletionStatus) {
            return;
        }

        $this->throwCourseException(
            message: "deletion of the course, with name: {$course->name}, failed",
            data: $courseDTO
        );
    }

    public function createCourse(CourseDTO $courseDTO)
    {
        $courseDTO = $courseDTO->withAddedby(
            $this->getModel($courseDTO->account, $courseDTO->accountId)
        );

        $this->checkAccountOwnership($courseDTO->addedby, $courseDTO);

        $course = $courseDTO->addedby->addedCourses()->create([
            'name' => $courseDTO->name,
            'state' => $courseDTO->owner === 'school' &&
                ($courseDTO->account !== 'admin' && $courseDTO->account !== 'school')
                ? 'PENDING' : 'ACCEPTED',
            'description' => $courseDTO->description,
            'stand_alone' => $courseDTO->standAlone,
        ]);

        if (is_null($course)) {
            $this->throwCourseException("course creation failed");
        }

        if ($courseDTO->isAttachment) {
            return $course;
        }

        $courseDTO = $courseDTO->withOwnedby(
            $this->getCourseOwnedby($course, $courseDTO)
        );

        $course->ownedby()->associate($courseDTO->ownedby);
        $course->save();

        $course = $this->addMainCourseDetails(
            course: $course,
            courseDTO: $courseDTO,
        );

        $courseDTO->methodType = 'created';
        $this->notifySchoolAdmins($course, $courseDTO);

        $this->broadcastCourse($course, $courseDTO);

        return $course;
    }

    private function getCourseOwnedby(
        $course,
        $courseDTO
    ) {
        if ($course->ownedby) {
            return $course->ownedby;
        }

        if ($courseDTO->account === $courseDTO->owner && $courseDTO->accountId === $courseDTO->ownerId) {
            return $courseDTO->addedby;
        }

        return $this->getModel($courseDTO->owner, $courseDTO->ownerId);
    }

    private function getEvent(
        $course,
        $courseDTO,
    ) {
        if ($courseDTO->methodType === 'created') {
            return new NewCourseEvent([
                'account' => $courseDTO->owner,
                'accountId' => $courseDTO->ownerId,
                'classes' => $course->classes->toArray(),
                'course' => new CourseResource($course),
            ]);
        }

        if ($courseDTO->methodType === 'updated') {
            return new UpdateCourseEvent([
                'account' => class_basename_lower($course->ownedby),
                'accountId' => $course->ownedby->id,
                'classes' => $course->classes->toArray(),
                'course' => new DashboardCourseResource($course),
            ]);
        }

        if ($courseDTO->methodType === 'deleted') {
            return new DeleteCourseEvent([
                'account' => class_basename_lower($course->ownedby),
                'accountId' => $course->ownedby->id,
                'classes' => $course->classes,
                'courseId' => $courseDTO->courseId,
            ]);
        }

        return null;
    }

    private function broadCastCourse(
        $course,
        $courseDTO,
    ) {
        $event = $this->getEvent($course, $courseDTO);

        if (is_null($event)) {
            return;
        }

        broadcast($event)->toOthers();
    }

    private function notifySchoolAdmins(
        $course,
        $courseDTO,
    ) {
        if ($courseDTO->ownedby->accountType !== 'school') {
            return;
        }

        $userIds = array_filter(
            $courseDTO->ownedby->getAdminIds(),
            function ($id) use ($courseDTO) {
                return $id !== $courseDTO->userId;
            }
        );

        $notification = $this->getNotification(
            $course,
            $courseDTO
        );

        if (is_null($notification)) {
            return;
        }

        Notification::send(
            User::whereIn('id', $userIds)->get(),
            $notification
        );
    }

    private function getNotification(
        $course,
        $courseDTO,
    ) {
        if ($courseDTO->methodType === 'created') {
            return new CourseCreatedNotification(
                new UserAccountResource($courseDTO->addedby),
                "created a course with the name: {$course->name}, 
                for {$courseDTO->ownedby->company_name}. Please go to dashboard to approve or otherwise."
            );
        }

        return null;
    }

    private function addMainCourseDetails(
        Course $course,
        CourseDTO $courseDTO,
    ) {
        $this->createAttachments(
            $course,
            $courseDTO->addedby,
            $courseDTO->attachments,
            $courseDTO->facilitate
        );

        if (!$course->stand_alone) {
            $attachedItems = $this->attachToItems(
                attachments: $courseDTO->items,
                attachable: $course,
                dto: $courseDTO
            );
        }

        $this->setPayment(
            paymentDTO: $courseDTO->paymentDTO?->withDashboardItem(
                $course
            )->withAddedby($courseDTO->addedby)
        );

        $this->createAutoDiscussion(
            item: $course,
            itemData: $courseDTO,
        );

        $course = $this->createCourseSections(
            course: $course,
            courseDTO: $courseDTO
        );

        $this->trackSchoolAdmin($course, $courseDTO);

        if (isset($attachedItems)) {

            $this->updateAccountCourseFacilitation(
                course: $course,
                courseDTO: $courseDTO,
                attachedItems: $attachedItems
            );
        }

        return $course->refresh();
    }

    private function updateAccountCourseFacilitation(
        $course,
        $courseDTO,
        $attachedItems = [],
        $detachedItems = []
    ) {
        if (
            $courseDTO->addedby->accountType !== 'facilitator' &&
            $courseDTO->addedby->accountType !== 'professional'
        ) {
            return;
        }

        if ($courseDTO->facilitate) {
            self::courseAttachItem($course->id, $courseDTO->addedby, 'facilitate');

            $this->attachFacilitatingAccountToItems(
                $course,
                $courseDTO,
                $attachedItems
            );

            return $course->refresh();
        }

        self::courseUnattachItem($course->id, $courseDTO->addedby);

        $this->detachFacilitatingAccountToItems(
            $course,
            $courseDTO,
            $detachedItems
        );

        return $course->refresh();
    }

    private function attachFacilitatingAccountToItems(
        $course,
        $courseDTO,
        $attachedItems,
    ) {
        foreach ($attachedItems as $itemable) {

            if ($itemable->doesntUseFacilitationDetail()) {
                continue;
            }

            FacilitationService::addFacilitationDetailsWithModels(
                $itemable,
                $course,
                $courseDTO->addedby
            );
        }
    }

    private function detachFacilitatingAccountToItems(
        $course,
        $courseDTO,
        $detachedItems
    ) {
        foreach ($detachedItems as $itemable) {

            if ($itemable->doesntUseFacilitationDetail()) {
                continue;
            }

            FacilitationService::removeFacilitationDetailsWithModels(
                $itemable,
                $course,
                $courseDTO->addedby
            );
        }
    }

    private function createCourseSections(Course $course, CourseDTO $courseDTO)
    {
        foreach ($courseDTO->sections as $section) {
            $course->courseSections()->create([
                'name' => $section->name,
                'description' => $section->description,
            ]);
        }

        return $course->refresh();
    }

    private function removeCourseSections(Course $course, CourseDTO $courseDTO)
    {
        if (!is_array($courseDTO->removedSections)) {
            return $course;
        }

        foreach ($courseDTO->removedSections as $section) {
            $courseSection = getYourEduModel('courseSection', $section->id);
            if (is_null($courseSection)) {
                throw new AccountNotFoundException("course section with id {$section->id} not found");
            }
            if ($courseSection->lessons->count()) {
                throw new CourseException("Please you cannot delete course section with {$section->id} because it has lessons. First delete the lessons and then continue.");
            }
            $courseSection->delete();
        }

        return $course->refresh();
    }

    private function editCourseSections(Course $course, CourseDTO $courseDTO)
    {
        if (!is_array($courseDTO->editedSections)) {
            return $course;
        }

        foreach ($courseDTO->editedSections as $section) {
            $course->courseSections()->where('id', $section->id)->first()?->update([
                'name' => $section->name,
                'description' => $section->description,
            ]);
        }

        return $course->refresh();
    }

    public function getCourse($courseId)
    {
        $course = $this->getModel('course', $courseId);


        return $course;
    }

    public function getCourses()
    {
    }

    public function updateCourse(CourseDTO $courseDTO)
    {
        ray($courseDTO)->green();

        $courseDTO = $courseDTO->withAddedby(
            $this->getModel($courseDTO->account, $courseDTO->accountId)
        );

        $this->checkAccountOwnership($courseDTO->addedby, $courseDTO);

        $course = $this->getModel('course', $courseDTO->courseId);

        $this->checkCourseAuthorization($course, $courseDTO);

        $course->update([
            'name' => $courseDTO->name,
            'state' => $courseDTO->state ?
                Str::upper($courseDTO->state) : "PENDING",
            'description' => $courseDTO->description,
        ]);

        $courseDTO->method = __METHOD__;
        $course = $this->addMainCourseDetails(
            course: $course,
            courseDTO: $courseDTO,
        );

        if (!$course->stand_alone) {
            $detachedItems = $this->detachFromItems(
                attachments: $courseDTO->removedItems,
                attachable: $course,
            );
        } else {
            $this->removeAllMainAttachments($course);
        }

        if (isset($detachedItems)) {

            $this->updateAccountCourseFacilitation(
                course: $course,
                courseDTO: $courseDTO,
                detachedItems: $detachedItems
            );
        }

        $this->removeAttachments(
            item: $course,
            account: ($courseDTO->account === 'facilitator' || $courseDTO->account === 'professional') ?
                $courseDTO->addedby : null,
            facilitate: $courseDTO->facilitate,
            attachments: $courseDTO->removedAttachments,
        );

        $this->removePayment(
            paymentDTO: $courseDTO->removedPaymentDTO
                ->withDashboardItem($course),
        );

        $course = $this->removeCourseSections(
            course: $course,
            courseDTO: $courseDTO
        );

        $course = $this->editCourseSections(
            course: $course,
            courseDTO: $courseDTO
        );

        $courseDTO->methodType = 'updated';
        $this->broadCastCourse($course, $courseDTO);

        return $course;
    }

    /**
     * this is to help remove course from all classes or programs 
     */
    private function removeAllMainAttachments(Course $course)
    {
        $course->programs()->detach();
        $course->classes()->detach();
    }

    public function checkCourseAuthorization($course, $courseDTO)
    {
        if ($this->hasAuthorization($course, $courseDTO->userId)) {
            return;
        }

        $this->throwCourseException(
            message: "You are not authorized to edit or delete the course with id {$course->id}",
            data: $courseDTO
        );
    }

    private function throwCourseException(
        $message,
        $data = null
    ) {
        throw new CourseException(
            message: $message,
            data: $data
        );
    }

    public function deleteCourse(CourseDTO $courseDTO)
    {
        ray($courseDTO)->green();
        $course = $this->getModel('course', $courseDTO->courseId);

        $this->checkCourseAuthorization($course, $courseDTO);

        $this->trackSchoolAdmin($course, $courseDTO);

        if ($courseDTO->action === 'undo') {
            return $this->changeState($course, 'accepted');
        }

        if ($this->paymentMadeFor($course) || $this->usedByAnotherItem($course)) {
            return $this->changeState($course, 'deleted');
        }

        $courseDTO->methodType = 'deleted';
        $this->broadcastCourse($course, $courseDTO);

        $course->delete();
        return null;
    }

    private function broadcastUpdate($course)
    {
        broadcast(new UpdateCourseEvent([
            'account' => class_basename_lower($course->ownedby),
            'accountId' => $course->ownedby->id,
            'classes' => $course->classes->toArray(),
            'course' => new DashboardCourseResource($course),
        ]))->toOthers();
    }

    private function paymentMadeFor($course)
    {
        if (
            $course->has('payments')
            ->orWhereHas('programs', function ($query) {
                $query->has('payments');
            })
            ->orWhereHas('classes', function ($query) {
                $query->has('payments');
            })
            ->count()
        ) {
            return true;
        }
        return false;
    }

    private function usedByAnotherItem($course)
    {
        if (
            $course->programs->whereNotNull('ownedby_type')->first() ||
            $course->classes->whereNotNull('ownedby_type')->first()
        ) {
            return true;
        }
        return false;
    }

    public static function courseAttachItem($courseId, $item, $activity = null)
    {
        $method = 'courses';
        if (class_basename_lower($item) === 'program') {
            $method = 'coursesService';
        }

        $activityArray = [];
        if (is_string($activity)) {
            $activityArray = ['activity' => Str::upper($activity)];
        }

        if ($item->$method->where('id', $courseId)->exists()) {
            return;
        }

        $item->$method()->attach($courseId, $activityArray);
        $item->save();
    }

    public static function courseUnattachItem($courseId, $item)
    {
        if ($item->courses->where('id', $courseId)->exists()) {
            $item->courses()->detach($courseId);
            $item->save();
        }
    }
}
