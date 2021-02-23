<?php

namespace App\Services;

use App\DTOs\CourseData;
use App\Events\DeleteCourseEvent;
use App\Events\NewCourseEvent;
use App\Events\UpdateCourseEvent;
use App\Exceptions\AccountNotFoundException;
use App\Exceptions\CourseException;
use App\Http\Resources\CourseResource;
use App\Http\Resources\DashboardCourseResource;
use App\Http\Resources\UserAccountResource;
use App\Notifications\CourseCreatedNotification;
use App\Traits\ClassCourseTrait;
use App\User;
use App\YourEdu\Course;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class CourseService
{
    use ClassCourseTrait;

    public function courseCreate($account,$accountId,$name,$description,$rationale,$aliases)
    {
        if ($account === 'learner' || $account === 'parent') {
            throw new CourseException('learner or parent can only create an alias of a subject');
        }

        $course = (new AttachmentService())->createAttachment($account,
            $accountId,'course',$name,$description,
            $rationale,$aliases);

        if (is_null($course)) {
            throw new CourseException('course was not created');
        }

        return $course;
    }

    public function courseAliasCreate($courseId,$account,$accountId,$name,$description)
    {
        $mainCourse = getYourEduModel('course',$courseId);
        if (is_null($mainCourse)) {
            throw new AccountNotFoundException("course not found with id {$courseId}");
        }
        $mainAccount = getYourEduModel($account,$accountId);
        if (is_null($mainAccount)) {
            throw new AccountNotFoundException("{$account} not found with id {$accountId}");
        }

        $alias = (new AttachmentService())->createAttachmentAlias($mainAccount,$mainCourse,
            $name,$description);

        if (is_null($alias)) {
            throw new CourseException('alias was not created');
        }

        return $mainCourse;
    }

    public function courseDelete($courseId,$id)
    {
        $course = getYourEduModel('course',$courseId);
        if (is_null($course)) {
            throw new AccountNotFoundException("course not found with id {$courseId}");
        }

        if ($course->addedby->user_id !== $id) {
            throw new CourseException('you cannot delete course you did not create');
        }

        $course->delete();

        return 'successful';
    }
    
    public function createCourse(CourseData $courseData)
    {
        ray($courseData)->green();
        $mainAccount = getYourEduModel($courseData->account,$courseData->accountId);
        if (is_null($mainAccount)) {
            throw new AccountNotFoundException("{$courseData->account} not found with id {$courseData->accountId}");
        }

        if (!$this->checkAccountOwnership($mainAccount,$courseData->userId)) {
            throw new CourseException("you do not own this account");
        }

        $course = $mainAccount->addedCourses()->create([
            'name' => $courseData->name,
            'state' => $courseData->owner === 'school' && 
                ($courseData->account !== 'admin' && $courseData->account !== 'school') 
                ? 'PENDING' : 'ACCEPTED',
            'description' => $courseData->description,
            'stand_alone' => $courseData->standAlone,
        ]);

        if (is_null($course)) {
            throw new CourseException("course creation failed");
        }

        if ($courseData->account === $courseData->owner && $courseData->accountId === $courseData->ownerId) {
            $mainOwner = $mainAccount;
        } else {
            $mainOwner = getYourEduModel($courseData->owner,$courseData->ownerId);
            if (is_null($mainOwner)) {
                throw new AccountNotFoundException("{$courseData->owner} not found with id {$courseData->ownerId}");
            }
        }        

        $course->ownedby()->associate($mainOwner);
        $course->save();

        $course = $this->itemCreateUpdateMethodParts(
            course: $course,
            mainAccount: $mainAccount,
            courseData: $courseData,
            method: __METHOD__
        );

        if ($course->state === 'PENDING') { //it is only pending if it belongs to school and it is created by a non owner or non admin
            $userIds = array_filter($mainOwner->getAdminIds(),function($id) use ($courseData){
                return $id !== $courseData->userId;
            });
            $name = $mainOwner->name ?? $mainAccount->company_name;
            Notification::send(User::whereIn('id',$userIds)->get(), 
                new CourseCreatedNotification(
                    new UserAccountResource($mainAccount),
                    "created a course with the name: {$course->name}, 
                    for {$name}. Please go to dashboard to approve or otherwise."
                )
            );
        }

        if ($courseData->owner === 'school') {
            broadcast(new NewCourseEvent([
                'account' => $courseData->owner,
                'accountId' => $courseData->ownerId,
                'classes' => $course->classes->toArray(),
                'course' => new CourseResource($course),
            ]))->toOthers();
        }

        return $course;
    }

    private function itemCreateUpdateMethodParts
    (
        Course $course,
        $mainAccount,
        CourseData $courseData,
        $method
    )
    {
        //attachments like courses programs grades
        $this->createAttachments(
            $course,
            $mainAccount,
            $courseData->attachments,
            $courseData->facilitate
        );

        //for classes and programs to which we may attach course
        if (!$course->stand_alone) {            
            $this->createMainAttachments(
                attachments: $courseData->classes,
                method: 'courses',
                itemId: $course->id,
                userId: $courseData->userId
            );
        }

        //set payment information
        $this->setPayment(
            item: $course,
            addedby: $mainAccount,
            paymentType: $courseData->type,
            paymentData: $courseData->paymentData,
        );

        //create auto discussion 
        $this->createAutoDiscussion(
            item: $course,
            itemData: $courseData,
        );

        //create sections
        $course = $this->createCourseSections(
            course: $course,
            courseData: $courseData
        );

        //track school activities
        if ($courseData->account === 'admin') {
            (new ActivityTrackService())->createActivityTrack(
                $course,$course->ownedby,$mainAccount,$method
            );
        } else if ($courseData->account === 'facilitator' || $courseData->account === 'professional') {
            //update course relations
            if ($courseData->facilitate) { //facilitate
                self::courseAttachItem($course->id,$mainAccount,'facilitate');
            } else {
                self::courseUnattachItem($course->id,$mainAccount);
            }
        }

        return $course->refresh();
    }

    private function createCourseSections(Course $course, CourseData $courseData)
    {
        forEach($courseData->sections as $section) {
            $course->courseSections()->create([
                'name' => $section->name,
                'description' => $section->description,
            ]);
        }

        return $course->refresh();
    }

    private function removeCourseSections(Course $course,CourseData $courseData)
    {
        if (!is_array($courseData->removedSections)) return $course;
        forEach($courseData->removedSections as $section) {
            $courseSection = getYourEduModel('courseSection',$section->id);
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

    private function editCourseSections(Course $course, CourseData $courseData)
    {
        if (!is_array($courseData->editedSections)) return $course;
        forEach($courseData->editedSections as $section) {
            $course->courseSections()->where('id', $section->id)->first()?->update([
                'name' => $section->name,
                'description' => $section->description,
            ]);
        }

        return $course->refresh();
    }
    
    public function getCourse($courseId)
    {
        $course = getYourEduModel('course',$courseId);
        if (is_null($course)) {
            throw new AccountNotFoundException("course not found with id {$courseId}");
        }

        return $course;
    }
    
    public function getCourses()
    {
        
    }
    
    public function updateCourse(CourseData $courseData)
    {
        ray($courseData)->green();
        //check account
        $mainAccount = getYourEduModel($courseData->account,$courseData->accountId);
        if (is_null($mainAccount)) {
            throw new AccountNotFoundException("$courseData->account not found with id {$courseData->courseId}");
        }

        if (!$this->checkAccountOwnership($mainAccount,$courseData->userId)) {
            throw new CourseException("you do not own this account");
        }
        //check course
        $course = getYourEduModel('course',$courseData->courseId);
        if (is_null($course)) {
            throw new AccountNotFoundException("course not found with id {$courseData->courseId}");
        }

        //check authorization
        $this->checkCourseAuthorization($course,$courseData->userId);

        //update course attributes
        $course->update([
            'name' => $courseData->name,
            'state' => Str::upper($courseData->state),
            'description' => $courseData->description,
        ]);

        $course = $this->itemCreateUpdateMethodParts(
            course: $course,
            mainAccount: $mainAccount,
            courseData: $courseData,
            method: __METHOD__
        );
        
        //for classes and programs from which we may detach course
        if (!$course->stand_alone) {            
            $this->removeMainAttachments(
                attachments: $courseData->removedClasses,
                method: 'courses',
                itemId: $course->id,
            );
        } else {
            $this->removeAllMainAttachments($course);
        }

        $this->removeAttachments( //remove attachments
            item: $course,
            account: ($courseData->account === 'facilitator' || $courseData->account === 'professional') ? $mainAccount : null,
            facilitate: $courseData->facilitate,
            attachments: $courseData->removedAttachments,
        );

        //set payment information
        $this->removePayment(
            item: $course,
            paymentData: $courseData->removedPaymentData,
        );

        //remove sections
        $course = $this->removeCourseSections(
            course: $course,
            courseData: $courseData
        );

        //edit sections
        $course = $this->editCourseSections(
            course: $course,
            courseData: $courseData
        );

        //broadcast
        $this->broadcastUpdate($course);

        //return course
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

    public function checkCourseAuthorization($course,$userId)
    {
        if (!$this->checkAuthorization($course,$userId)) {
            ray('enters');
            throw new CourseException("You are not authorized to edit or delete the course with id {$course->id}");
        }
    }

    public function deleteCourse(CourseData $courseData)
    {
        ray($courseData)->green();
        $course = getYourEduModel('course',$courseData->courseId);
        if (is_null($course)) {
            throw new AccountNotFoundException("course not found with id {$courseData->courseId}");
        }

        $this->checkCourseAuthorization($course,$courseData->userId);

        if ($courseData->adminId) {
            $admin = getYourEduModel('admin',$courseData->adminId);
            (new ActivityTrackService())->createActivityTrack(
                $course,$course->ownedby,$admin,__METHOD__
            );
        }

        if ($courseData->action === 'undo') {
            return $this->changeState($course,'accepted');
        } else if($courseData->action === 'delete') {
            //check if someone has subsribed or paid or used by a program
            if ($this->paymentMadeFor($course) || $this->usedByAnother($course)) {
                return $this->changeState($course,'deleted');
            } else {
                
                broadcast(new DeleteCourseEvent([
                    'account' => class_basename_lower($course->ownedby),
                    'accountId' => $course->ownedby->id,
                    'classes' => $course->classes,
                    'courseId' => $courseData->courseId,
                ]))->toOthers();

                $course->delete();
                return null;
            }
        }
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
                ->orWhereHas('programs',function($query) {
                    $query->has('payments');
                })
                ->orWhereHas('classes',function($query) {
                    $query->has('payments');
                })
                ->count()
        ) {
            return true;
        }
        return false;
    }

    private function usedByAnother($course)
    {
        if ($course->programs->whereNotNull('ownedby_type')->first() ||
            $course->classes->whereNotNull('ownedby_type')->first()) {
            return true;
        }
        return false;
    }

    public static function courseAttachItem($courseId,$item,$activity)
    {
        if ($item::class === 'App\\YourEdu\\Program') {
            $method = 'coursesService';
        } else {
            $method = 'courses';
        }
        if (is_null(
            $item->$method->where('id',$courseId)->first()
        )) {
            $item->$method()->attach($courseId,['activity' => Str::upper($activity)]);
            $item->save();
        }
    }

    public static function courseUnattachItem($courseId,$item)
    {
        if (!is_null(
            $item->courses->where('id',$courseId)->first()
        )) {
            $item->courses()->detach($courseId);
            $item->save();
        }
    }
}