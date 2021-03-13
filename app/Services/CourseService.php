<?php

namespace App\Services;

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
    
    public function createCourse(CourseDTO $courseDTO)
    {
        ray($courseDTO)->green();
        $mainAccount = getYourEduModel($courseDTO->account,$courseDTO->accountId);
        if (is_null($mainAccount)) {
            throw new AccountNotFoundException("{$courseDTO->account} not found with id {$courseDTO->accountId}");
        }

        $this->checkAccountOwnership($mainAccount,$courseDTO);

        $course = $mainAccount->addedCourses()->create([
            'name' => $courseDTO->name,
            'state' => $courseDTO->owner === 'school' && 
                ($courseDTO->account !== 'admin' && $courseDTO->account !== 'school') 
                ? 'PENDING' : 'ACCEPTED',
            'description' => $courseDTO->description,
            'stand_alone' => $courseDTO->standAlone,
        ]);

        if (is_null($course)) {
            throw new CourseException("course creation failed");
        }

        if ($courseDTO->account === $courseDTO->owner && $courseDTO->accountId === $courseDTO->ownerId) {
            $mainOwner = $mainAccount;
        } else {
            $mainOwner = getYourEduModel($courseDTO->owner,$courseDTO->ownerId);
            if (is_null($mainOwner)) {
                throw new AccountNotFoundException("{$courseDTO->owner} not found with id {$courseDTO->ownerId}");
            }
        }        

        $course->ownedby()->associate($mainOwner);
        $course->save();

        $course = $this->itemCreateUpdateMethodParts(
            course: $course,
            mainAccount: $mainAccount,
            courseDTO: $courseDTO,
            method: __METHOD__
        );

        if ($course->state === 'PENDING') { //it is only pending if it belongs to school and it is created by a non owner or non admin
            $userIds = array_filter($mainOwner->getAdminIds(),function($id) use ($courseDTO){
                return $id !== $courseDTO->userId;
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

        if ($courseDTO->owner === 'school') {
            broadcast(new NewCourseEvent([
                'account' => $courseDTO->owner,
                'accountId' => $courseDTO->ownerId,
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
        CourseDTO $courseDTO,
        $method
    )
    {
        //attachments like courses programs grades
        $this->createAttachments(
            $course,
            $mainAccount,
            $courseDTO->attachments,
            $courseDTO->facilitate
        );

        //for classes and programs to which we may attach course
        if (!$course->stand_alone) {            
            $this->createMainAttachments(
                attachments: $courseDTO->classes,
                method: 'courses',
                itemId: $course->id,
                userId: $courseDTO->userId
            );
        }

        //set payment information
        $this->setPayment(
            item: $course,
            addedby: $mainAccount,
            paymentType: $courseDTO->type,
            paymentData: $courseDTO->paymentData,
        );

        //create auto discussion 
        $this->createAutoDiscussion(
            item: $course,
            itemData: $courseDTO,
        );

        //create sections
        $course = $this->createCourseSections(
            course: $course,
            courseDTO: $courseDTO
        );

        //track school activities
        if ($courseDTO->account === 'admin') {
            (new ActivityTrackService())->trackActivity(
                $course,$course->ownedby,$mainAccount,$method
            );
        } else if ($courseDTO->account === 'facilitator' || $courseDTO->account === 'professional') {
            //update course relations
            if ($courseDTO->facilitate) { //facilitate
                self::courseAttachItem($course->id,$mainAccount,'facilitate');
            } else {
                self::courseUnattachItem($course->id,$mainAccount);
            }
        }

        return $course->refresh();
    }

    private function createCourseSections(Course $course, CourseDTO $courseDTO)
    {
        forEach($courseDTO->sections as $section) {
            $course->courseSections()->create([
                'name' => $section->name,
                'description' => $section->description,
            ]);
        }

        return $course->refresh();
    }

    private function removeCourseSections(Course $course,CourseDTO $courseDTO)
    {
        if (!is_array($courseDTO->removedSections)) return $course;
        forEach($courseDTO->removedSections as $section) {
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

    private function editCourseSections(Course $course, CourseDTO $courseDTO)
    {
        if (!is_array($courseDTO->editedSections)) return $course;
        forEach($courseDTO->editedSections as $section) {
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
    
    public function updateCourse(CourseDTO $courseDTO)
    {
        ray($courseDTO)->green();
        //check account
        $mainAccount = getYourEduModel($courseDTO->account,$courseDTO->accountId);
        if (is_null($mainAccount)) {
            throw new AccountNotFoundException("$courseDTO->account not found with id {$courseDTO->courseId}");
        }

        $this->checkAccountOwnership($mainAccount,$courseDTO);
        
        //check course
        $course = getYourEduModel('course',$courseDTO->courseId);
        if (is_null($course)) {
            throw new AccountNotFoundException("course not found with id {$courseDTO->courseId}");
        }

        //check authorization
        $this->checkCourseAuthorization($course,$courseDTO->userId);

        //update course attributes
        $course->update([
            'name' => $courseDTO->name,
            'state' => Str::upper($courseDTO->state),
            'description' => $courseDTO->description,
        ]);

        $course = $this->itemCreateUpdateMethodParts(
            course: $course,
            mainAccount: $mainAccount,
            courseDTO: $courseDTO,
            method: __METHOD__
        );
        
        //for classes and programs from which we may detach course
        if (!$course->stand_alone) {            
            $this->removeMainAttachments(
                attachments: $courseDTO->removedClasses,
                method: 'courses',
                itemId: $course->id,
            );
        } else {
            $this->removeAllMainAttachments($course);
        }

        $this->removeAttachments( //remove attachments
            item: $course,
            account: ($courseDTO->account === 'facilitator' || $courseDTO->account === 'professional') ? $mainAccount : null,
            facilitate: $courseDTO->facilitate,
            attachments: $courseDTO->removedAttachments,
        );

        //set payment information
        $this->removePayment(
            item: $course,
            paymentData: $courseDTO->removedPaymentData,
        );

        //remove sections
        $course = $this->removeCourseSections(
            course: $course,
            courseDTO: $courseDTO
        );

        //edit sections
        $course = $this->editCourseSections(
            course: $course,
            courseDTO: $courseDTO
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
        if ($this->doesntHaveAuthorization($course,$userId)) {
            return;
        }
        throw new CourseException("You are not authorized to edit or delete the course with id {$course->id}");
    }

    public function deleteCourse(CourseDTO $courseDTO)
    {
        ray($courseDTO)->green();
        $course = getYourEduModel('course',$courseDTO->courseId);
        if (is_null($course)) {
            throw new AccountNotFoundException("course not found with id {$courseDTO->courseId}");
        }

        $this->checkCourseAuthorization($course,$courseDTO->userId);

        if ($courseDTO->adminId) {
            $admin = getYourEduModel('admin',$courseDTO->adminId);
            (new ActivityTrackService())->trackActivity(
                $course,$course->ownedby,$admin,__METHOD__
            );
        }

        if ($courseDTO->action === 'undo') {
            return $this->changeState($course,'accepted');
        } else if($courseDTO->action === 'delete') {
            //check if someone has subsribed or paid or used by a program
            if ($this->paymentMadeFor($course) || $this->usedByAnother($course)) {
                return $this->changeState($course,'deleted');
            } else {
                
                broadcast(new DeleteCourseEvent([
                    'account' => class_basename_lower($course->ownedby),
                    'accountId' => $course->ownedby->id,
                    'classes' => $course->classes,
                    'courseId' => $courseDTO->courseId,
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