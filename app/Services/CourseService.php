<?php

namespace App\Services;

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
        $mainCourse = getAccountObject('course',$courseId);
        if (is_null($mainCourse)) {
            throw new AccountNotFoundException("course not found with id {$courseId}");
        }
        $mainAccount = getAccountObject($account,$accountId);
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
        $course = getAccountObject('course',$courseId);
        if (is_null($course)) {
            throw new AccountNotFoundException("course not found with id {$courseId}");
        }

        if ($course->addedby->user_id !== $id) {
            throw new CourseException('you cannot delete course you did not create');
        }

        $course->delete();

        return 'successful';
    }
    
    // for actual courses with lessons
    public function createCourse($account,$accountId,$id,$courseData)
    {
        $mainAccount = getAccountObject($account,$accountId);
        if (is_null($mainAccount)) {
            throw new AccountNotFoundException("{$account} not found with id {$accountId}");
        }

        if ($mainAccount->user_id !== $id) {
            throw new CourseException("you do not own this account");
        }

        $course = $mainAccount->addedCourses()->create([
            'name' => $courseData['name'],
            'state' => $courseData['owner'] === 'school' && 
                ($account !== 'admin' || $account !== 'school') 
                ? 'PENDING' : 'ACCEPTED',
            'description' => $courseData['description'],
        ]);

        if (is_null($course)) {
            throw new CourseException("course creation failed");
        }

        if ($account === $courseData['owner'] && $accountId === $courseData['ownerId']) {
            $mainOwner = $mainAccount;
        } else {
            $mainOwner = getAccountObject($courseData['owner'],$courseData['ownerId']);
            if (is_null($mainOwner)) {
                throw new AccountNotFoundException("{$courseData['owner']} not found with id {$courseData['ownerId']}");
            }
        }        

        $course->ownedby()->associate($mainOwner);
        $course->save();

        if ($courseData['facilitate']) {
            self::courseAttachItem($course->id,$mainAccount,'facilitate');
        }

        //attachments like courses programs grades
        $this->createAttachments(
            $course,
            $mainAccount,
            $courseData['attachments'],
            $courseData['facilitate']
        );

        //for classes we may attach course to
        if (is_array($courseData['classes'])) {
            foreach ($courseData['classes'] as $cl) {
                $actualClass = getAccountObject('class',$cl->id);
                if (!is_null($actualClass)) {                
                    $actualClass->courses()->attach($course->id);
                    $actualClass->save();
                }
            }
        } else {
            $courseData['classes'] = [];
        }

        //set payment information
        $this->setPayment(
            item: $course,
            addedby: $mainAccount,
            paymentType: $courseData['type'],
            paymentData: $courseData['paymentData'],
        );

        //create auto discussion 
        if ($courseData['discussionData']) {
            $discussion = (new DiscussionService())->createDiscussion(
                $account,
                $accountId,
                $courseData['discussionData']->title,
                $courseData['discussionData']->preamble,
                $courseData['discussionData']->restricted,
                $courseData['discussionData']->type,
                $courseData['discussionData']->allowed,
                $courseData['discussionFiles'],
                null
            );
            $discussion->discussionfor()->associate($course);
            $discussion->save();
        }

        //track school activities
        if ($account === 'admin') {
            (new ActivityTrackService())->createActivityTrack(
                $course,$mainOwner,$mainAccount,__METHOD__
            );
        }

        if ($course->state === 'PENDING') { //it is only pending if it belongs to school and it is created by a non owner or non admin
            $userIds = array_filter(getAdminIds($mainOwner),function($userId) use ($id){
                return $userId !== $id;
            });
            Notification::send(User::whereIn('id',$userIds)->get(), 
                new CourseCreatedNotification(
                    new UserAccountResource($mainAccount),
                    "created a course with the name: {$course->name}, 
                    for {$mainOwner->name}. Please go to dashboard to approve or otherwise."
                )
            );
        }

        if ($courseData['owner'] === 'school') {
            broadcast(new NewCourseEvent([
                'account' => $courseData['owner'],
                'accountId' => $courseData['ownerId'],
                'classes' => $courseData['classes'],
                'course' => new CourseResource($course),
            ]))->toOthers();
        }

        return $course;
    }
    
    public function getCourse($courseId)
    {
        $course = getAccountObject('course',$courseId);
        if (is_null($course)) {
            throw new AccountNotFoundException("course not found with id {$courseId}");
        }

        return $course;
    }
    
    public function getCourses()
    {
        
    }
    
    public function updateCourse($account,$accountId,$courseId,$userId,$courseData)
    {
        //check account
        $mainAccount = getAccountObject($account,$accountId);
        if (is_null($mainAccount)) {
            throw new AccountNotFoundException("$account not found with id {$courseId}");
        }
        //check course
        $course = getAccountObject('course',$courseId);
        if (is_null($course)) {
            throw new AccountNotFoundException("course not found with id {$courseId}");
        }

        //check authorization
        $this->checkCourseAuthorization($course,$userId);

        //update course attributes
        $course->update([
            'name' => $courseData['name'],
            'state' => Str::upper($courseData['state']),
            'description' => $courseData['description'],
        ]);

        //update course relations
        if ($courseData['facilitate']) { //facilitate
            self::courseAttachItem($courseId,$mainAccount,'facilitate');
        } else {
            self::courseUnattachItem($courseId,$mainAccount);
        }

        $this->createAttachments( //attachments like courses programs grades
            $course,
            $mainAccount,
            $courseData['attachments'],
            $courseData['facilitate']
        );  

        //track school activities
        if ($account === 'admin') {
            (new ActivityTrackService())->createActivityTrack(
                $course,$course->ownedby,$mainAccount,__METHOD__
            );
        }   

        //broadcast
        $ownedby = $course->ownedby;
        $classes = $course->classes;

        if (getAccountString($course) === 'school') {            
            broadcast(new UpdateCourseEvent([
                'account' => getAccountString($ownedby),
                'accountId' => $ownedby->id,
                'classes' => $classes,
                'courseId' => $courseId,
            ]))->toOthers();
        }   

        //return course
        return $course;
    }

    public function checkCourseAuthorization($course,$userId)
    {
        if (!$this->checkAuthorization($course,$userId)) {
            throw new CourseException("You are not authorized to edit or delete the course with id {$course->id}");
        }
    }

    public function deleteCourse($courseId,$userId,$adminId,$action)
    {
        $course = getAccountObject('course',$courseId);
        if (is_null($course)) {
            throw new AccountNotFoundException("course not found with id {$courseId}");
        }

        $this->checkCourseAuthorization($course,$userId);

        if ($adminId) {
            $admin = getAccountObject('admin',$adminId);
            (new ActivityTrackService())->createActivityTrack(
                $course,$course->ownedby,$admin,__METHOD__
            );
        }
        $ownedby = $course->ownedby;
        $classes = $course->classes;

        if ($action === 'undo') {
            return $this->changeState($ownedby,$classes,$course,'accepted');
        } else if($action === 'delete') {
            //check if someone has subsribed or paid or used by a program
            if ($this->paymentMadeFor($course) || $this->usedByAnother($course)) {
                return $this->changeState($ownedby,$classes,$course,'deleted');
            } else {
                $course->delete();
                
                broadcast(new DeleteCourseEvent([
                    'account' => getAccountString($ownedby),
                    'accountId' => $ownedby->id,
                    'classes' => $classes,
                    'courseId' => $courseId,
                ]))->toOthers();

                return null;
            }
        }
    }

    private function broadcastUpdate($ownedby,$classes,$course)
    {
        broadcast(new UpdateCourseEvent([
            'account' => getAccountString($ownedby),
            'accountId' => $ownedby->id,
            'classes' => $classes,
            'course' => new DashboardCourseResource($course),
        ]))->toOthers();
    }

    private function paymentMadeFor($course)
    {
        return true;
        if (
            $course->whereHas('payments')
                ->orWhereHas('programs',function($query) {
                    $query->whereHas('payments');
                })
                ->orWhereHas('classes',function($query) {
                    $query->whereHas('payments');
                })
                ->first()
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
        if (is_null(
            $item->courses->where('id',$courseId)->first()
        )) {
            $item->courses()->attach($courseId,['activity' => Str::upper($activity)]);
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