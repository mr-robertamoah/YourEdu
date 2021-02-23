<?php 

namespace App\Services;

use App\DTOs\LessonData;
use App\DTOs\LinkData;
use App\Events\DeleteLessonEvent;
use App\Events\NewLessonEvent;
use App\Events\UpdateLessonEvent;
use App\Exceptions\AccountNotFoundException;
use App\Exceptions\LessonException;
use App\Http\Resources\DashboardLessonResource;
use App\Http\Resources\LessonResource;
use App\Http\Resources\UserAccountResource;
use App\Notifications\LessonCreatedNotification;
use App\Traits\ClassCourseTrait;
use App\User;
use App\YourEdu\Lesson;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class LessonService
{
    use ClassCourseTrait;

    /**
     * this helps to determine which state to set when creating lesson
     */
    private function returnState($account,$owner,$main)
    {
        if (!$main) return null;
        return $owner === 'school' && 
            ($account !== 'admin' && $account !== 'school') 
            ? 'PENDING' : 'ACCEPTED';
    }
    
    public function createLesson(LessonData $lessonData) //main determines if lesson is for post or not
    {
        $mainAccount = getYourEduModel($lessonData->account,$lessonData->accountId);
        if (is_null($mainAccount)) {
            throw new AccountNotFoundException("{$lessonData->account} not found with id {$lessonData->accountId}");
        }
        ray($mainAccount,$lessonData)->green();
        if (!$this->checkAccountOwnership($mainAccount,$lessonData->userId)) {
            throw new LessonException("you do not own this account");
        }

        $lesson = $mainAccount->addedLessons()->create([
            'title' => $lessonData->title,
            'state' => $this->returnState($lessonData->owner,$lessonData->account,$lessonData->main),
            'description' => $lessonData->description,
        ]);

        if (is_null($lesson)) {
            throw new LessonException("lesson creation failed");
        }

        if ($lessonData->account === $lessonData->owner && $lessonData->accountId === $lessonData->ownerId) {
            $mainOwner = $mainAccount;
        } else {
            $mainOwner = getYourEduModel($lessonData->owner,$lessonData->ownerId);
            if ($lessonData->main && is_null($mainOwner)) {
                throw new AccountNotFoundException("{$lessonData->owner} not found with id {$lessonData->ownerId}");
            }
        }        

        $lesson->ownedby()->associate($mainOwner);
        $lesson->save();

        if ($lessonData->main) {
            
            $lesson = $this->itemCreateUpdateMethodParts(
                lesson: $lesson,
                mainAccount: $mainAccount,
                lessonData: $lessonData
            );

            if ($lessonData->owner === 'school') { //it is only pending if it belongs to school and it is created by a non owner or non admin
                $userIds = array_filter($mainOwner->getAdminIds(),
                    function($id) use ($lessonData){
                        return $id !== $lessonData->userId;
                    }
                );
                $name = $mainOwner->name ?? $mainAccount->company_name;
                Notification::send(User::whereIn('id',$userIds)->get(), 
                    new LessonCreatedNotification(
                        new UserAccountResource($mainAccount),
                        "created a lesson with the name: {$lesson->title}, 
                        for {$name}. Please go to dashboard to approve or otherwise."
                    )
                );

                broadcast(new NewLessonEvent([
                    'account' => $lessonData->owner,
                    'accountId' => $lessonData->ownerId,
                    'classes' => $lesson->classes->toArray(),
                    'extracurriculums' => $lesson->extracurriculums->toArray(),
                    'courseSections' => $lesson->courseSections->toArray(),
                    'courses' => $lesson->courses()->hasOwner()->get()->toArray(),
                    'lesson' => new LessonResource($lesson),
                ]))->toOthers();
            }
        }

        return $lesson;
    }

    private function itemCreateUpdateMethodParts
    (
        $lesson,
        $mainAccount,
        LessonData $lessonData,
    )
    {
        //attachments like lessons programs grades
        $this->createAttachments(
            $lesson,
            $mainAccount,
            $lessonData->attachments,
            true
        );

        //for classes and courses to which we may attach lesson
        $lesson = $this->createMainAttachments(
            attachments: $lessonData->items,
            method: 'lessons',
            lesson: $lesson,
            userId: $lessonData->userId,
            activity: $lessonData->intro ? 'INTRO' : ($lessonData->free ? 
                'FREE' : null)
        );

        //set payment information
        $this->setPayment(
            item: $lesson,
            addedby: $mainAccount,
            paymentType: $lessonData->type,
            paymentData: $lessonData->paymentData,
        );

        //create auto discussion 
        $this->createAutoDiscussion(
            item: $lesson,
            itemData: $lessonData
        );

        //lesson files
        $this->addFiles(
            lesson: $lesson,
            account: $mainAccount,
            files: $lessonData->files
        );

        $this->createLinks($mainAccount,$lesson,$lessonData->links);

        return $lesson->refresh();
    }

    private function removeMainAttachments
    (
        array $attachments,
        string $method,
        Lesson $lesson
    )
    {
        foreach ($attachments as $attachment) {
            if (property_exists($attachment,"classId")) {
                $class = getYourEduModel('class',$attachment->classId);
            } else {
                $class = null;
            }
            $attachable = getYourEduModel($attachment->type, $attachment->id);
            if (!is_null($class) && $class->$method->where('id',$lesson->id)->count() > 0) {
                $class->$method()->detach($lesson->id);
            } else if ($attachable->$method->where('id',$lesson->id)->count() > 0) {
                $attachable->$method()->detach($lesson->id);
            }
        }

        return $lesson->refresh();
    }

    private function createMainAttachments
    (
        array $attachments,
        $method,
        Lesson $lesson, //lesson
        $userId,
        $activity
    )
    {
        foreach ($attachments as $attachment) {
            if (property_exists($attachment,"classId")) {
                $class = getYourEduModel('class',$attachment->classId);
            } else {
                $class = null;
            }
            $attachable = getYourEduModel($attachment->type, $attachment->id);
            if (is_null($attachable)) {
                throw new AccountNotFoundException("{$attachment->type} with id {$attachment->id} not found.");
            }
            if (!is_null($class)) {
                if ($this->doesntAlreadyHaveLesson($class,$lesson)) {
                    if ($activity === 'INTRO') $this->doesntHaveIntro($class);
                    
                    $class->$method()->attach($lesson->id,[
                        'activity'=>$activity,
                        'subject_id' => $attachment->id
                    ]);
                }
            } else {
                if ($this->doesntAlreadyHaveLesson($attachable,$lesson)) {
                    if ($activity === 'INTRO') $this->doesntHaveIntro($attachable);
                    if ($attachment->type === 'courseSection') {
                        ray($attachable,'course section')->green();
                        $attachable->$method()->attach($lesson->id,[
                            'lesson_number' => $attachable->lessons->last()?->lesson_number + 1
                        ]);
                    } else {
                        ray($attachable,'not in course')->green();
                        $attachable->$method()->attach($lesson->id,[
                            'activity' => $activity
                        ]);
                    }
                }
            }
        }

        return $lesson->refresh();
    }

    private function doesntAlreadyHaveLesson($item,$lesson)
    {
        ray($item->lessons->where('id',$lesson->id)->count())->green();
        if ($item->lessons->where('id',$lesson->id)->count() > 0) {
            return false;
        }
        return true;
    }

    private function doesntHaveIntro($item) 
    {
        if ($item->lessons()->where('activity','INTRO')->count() > 0) {
            $itemType = class_basename_lower($item);
            throw new LessonException("there is already an introductory lesson for {$itemType} with id {$item->id}");
        }
    }

    private function addFiles(Lesson $lesson,$account,array $files,)
    {
        foreach ($files as $file) {
            FileService::createAndAttachFiles(
                file: $file,
                account: $account,
                item: $lesson
            );
        }

        return $lesson->refresh();
    }

    private function createLinks($account,$lesson, $links)
    {
        if (is_array($links)) {
            foreach ($links as $link) {
                $link->linkable = $lesson;
                $link->addedby = $account;
                LinkService::createLink($link);
            }
        }
    }
    
    private function editLinks(array $links)
    {
        foreach ($links as $link) {
            LinkService::editLink($link);
        }
    }
    
    private function deleteLinks(array $links)
    {
        foreach ($links as $link) {
            LinkService::deleteLink($link);
        }
    }

    private function deleteFiles(array $files,Lesson $lesson) {
        foreach ($files as $file) {
            FileService::deleteAndUnattachFilesFromObject($file,$lesson);
        }
    }
    
    public function getLesson($lessonId)
    {
        $lesson = getYourEduModel('lesson',$lessonId);
        if (is_null($lesson)) {
            throw new AccountNotFoundException("lesson not found with id {$lessonId}");
        }

        return $lesson;
    }
    
    public function getLessons()
    {
        
    }
    
    public function updateLesson(LessonData $lessonData)
    {
        ray($lessonData)->green();
        //check account
        $mainAccount = getYourEduModel($lessonData->account,$lessonData->accountId);
        if (is_null($mainAccount)) {
            throw new AccountNotFoundException("$lessonData->account not found with id {$lessonData->lessonId}");
        }

        if (!$this->checkAccountOwnership($mainAccount,$lessonData->userId)) {
            throw new LessonException("you do not own this account");
        }
        //check lesson
        $lesson = getYourEduModel('lesson',$lessonData->lessonId);
        if (is_null($lesson)) {
            throw new AccountNotFoundException("lesson not found with id {$lessonData->lessonId}");
        }

        //check authorization
        $this->checkLessonAuthorization($lesson,$lessonData->userId);

        //update lesson attributes
        $lesson->update([
            'title' => $lessonData->title,
            'state' => Str::upper($lessonData->state),
            'description' => $lessonData->description,
        ]);
        
        $this->itemCreateUpdateMethodParts(
            lesson: $lesson,
            mainAccount: $mainAccount,
            lessonData: $lessonData,
        );

        //for classes and programs from which we may detach lesson
        $lesson = $this->removeMainAttachments(
            attachments: $lessonData->removedItems,
            method: 'lessons',
            lesson: $lesson,
        );

        $this->removeAttachments( //remove attachments
            item: $lesson,
            account: ($lessonData->account === 'facilitator' || $lessonData->account === 'professional') ? $mainAccount : null,
            facilitate: false,
            attachments: $lessonData->removedAttachments,
        );

        //set payment information
        $this->removePayment(
            item: $lesson,
            paymentData: $lessonData->removedPaymentData,
        );

        //files
        $this->deleteFiles(
            files: $lessonData->removedFiles, 
            lesson: $lesson
        );

        //track school activities
        if ($lessonData->account === 'admin') {
            (new ActivityTrackService())->createActivityTrack(
                $lesson,$lesson->ownedby,$mainAccount,__METHOD__
            );
        }

        //links
        $this->editLinks($lessonData->editedLinks);
        $this->deleteLinks($lessonData->removedLinks);

        //broadcast     
        $this->broadcastUpdate($lesson);

        //return lesson
        return $lesson;
    }

    public function checkLessonAuthorization($lesson,$userId)
    {
        if (!$this->checkAuthorization($lesson,$userId)) {
            throw new LessonException("You are not authorized to edit or delete the lesson with id {$lesson->id}");
        }
    }

    public function deleteLesson(LessonData $lessonData)
    {
        $lesson = getYourEduModel('lesson',$lessonData->lessonId);
        if (is_null($lesson)) {
            throw new AccountNotFoundException("lesson not found with id {$lessonData->lessonId}");
        }

        $this->checkLessonAuthorization($lesson,$lessonData->userId);

        if ($lessonData->adminId) {
            $admin = getYourEduModel('admin',$lessonData->adminId);
            (new ActivityTrackService())->createActivityTrack(
                $lesson,$lesson->ownedby,$admin,__METHOD__
            );
        }

        if ($lessonData->action === 'undo') {
            return $this->changeState($lesson,'accepted');
        } else if($lessonData->action === 'delete') {
            //check if someone has subsribed or paid or used by a program
            if ($this->paymentMadeFor($lesson)) {
                return $this->changeState($lesson,'deleted');
            } else {
                
                broadcast(new DeleteLessonEvent([
                    'account' => class_basename_lower($lesson->ownedby),
                    'accountId' => $lesson->ownedby->id,
                    'classes' => $lesson->classes->toArray(),
                    'courses' => $lesson->courses()->hasOwner()->get()->toArray(),
                    'courseSections' => $lesson->courseSections->toArray(),
                    'extracurriculums' => $lesson->extracurriculums->toArray(),
                    'lessonId' => $lessonData->lessonId,
                ]))->toOthers();

                $lesson->delete();
                return null;
            }
        }
    }

    private function broadcastUpdate($lesson)
    {
        broadcast(new UpdateLessonEvent([
            'account' => class_basename_lower($lesson->ownedby),
            'accountId' => $lesson->ownedby->id,
            'classes' => $lesson->classes->toArray(),
            'courses' => $lesson->courses()->hasOwner()->get()->toArray(),
            'courseSections' => $lesson->courseSections->toArray(),
            'extracurriculums' => $lesson->extracurriculums->toArray(),
            'lesson' => new DashboardLessonResource($lesson),
        ]))->toOthers();
    }

    private function paymentMadeFor($lesson)
    {
        // return true;
        if (
            $lesson->has('payments')
                ->whereHas('courses',function($query) {
                    $query->has('payments');
                })
                ->orWhereHas('courseSections',function($query) {
                    $query->whereHas('course',function($query) {
                        $query->has('payments');
                    });
                })
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

    private function usedByAnother($lesson)
    {
        
    }

    public static function lessonAttachItem($lessonId,$item,$activity)
    {
        if (is_null(
            $item->lessons->where('id',$lessonId)->first()
        )) {
            $item->lessons()->attach($lessonId,['activity' => Str::upper($activity)]);
            $item->save();
        }
    }

    public static function lessonUnattachItem($lessonId,$item)
    {
        if (!is_null(
            $item->lessons->where('id',$lessonId)->first()
        )) {
            $item->lessons()->detach($lessonId);
            $item->save();
        }
    }
}