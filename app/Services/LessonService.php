<?php 

namespace App\Services;

use App\DTOs\LessonDTO;
use App\Events\DeleteLessonEvent;
use App\Events\NewLessonEvent;
use App\Events\UpdateLessonEvent;
use App\Exceptions\AccountNotFoundException;
use App\Exceptions\LessonException;
use App\Http\Resources\DashboardLessonResource;
use App\Http\Resources\LessonResource;
use App\Http\Resources\UserAccountResource;
use App\Notifications\LessonCreatedNotification;
use App\Notifications\LessonDeletedNotification;
use App\Notifications\LessonUpdatedNotification;
use App\Traits\ClassCourseTrait;
use App\User;
use App\YourEdu\Lesson;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class LessonService
{
    use ClassCourseTrait;

    public function __construct(private int $lessonFileTypeMax = 1) 
    {

    }

    public function createLesson(LessonDTO $lessonDTO)
    {
        $lessonDTO = $this->setLessonAddedby($lessonDTO);

        $this->checkAccountOwnership($lessonDTO->addedby,$lessonDTO);

        $lesson = $this->createOrUpdateLesson($lessonDTO, 'create');

        $lessonDTO = $lessonDTO->withLesson($lesson);

        $lessonDTO = $this->setLessonOwnedby($lessonDTO);
        
        $this->checkLessonFiles($lesson, $lessonDTO);

        $lesson = $this->attachLessonToOwnedby($lesson, $lessonDTO);

        $lesson = $this->attachLessonToItem($lesson, $lessonDTO);

        $this->createAttachments(
            $lesson,
            $lesson->addedby,
            $lessonDTO->attachments,
            true
        );

        $lesson = $this->addFiles($lesson, $lessonDTO);

        $lesson = $this->createLinks($lesson,$lessonDTO->links);

        $lesson = $this->addMainLessonDetails($lesson, $lessonDTO);

        $this->notifySchoolAdmins($lesson, $lessonDTO);

        $this->broadcastNewLesson($lesson, $lessonDTO);

        $this->trackAdminActivity($lesson, $lessonDTO, __METHOD__);

        return $lesson;
    }

    private function checkLessonFiles
    (
        Lesson $lesson,
        LessonDTO $lessonDTO
    )
    {
        $lessonFilesDTO = FileService::countPossibleItemFiles(
            $lesson,
            $lessonDTO
        );

        if ($lessonFilesDTO->totalFiles() < 1) {
            $this->throwLessonException(
                message: "a lesson should have at least one file (image, video or audio)",
                data: $lessonDTO
            );
        }

        if ($lessonFilesDTO->imagesCount > $this->lessonFileTypeMax) {
            $this->throwLessonException(
                message: "lesson's images cannot be more than {$this->lessonFileTypeMax}",
                data: $lessonDTO
            );
        }

        if ($lessonFilesDTO->videosCount > $this->lessonFileTypeMax) {
            $this->throwLessonException(
                message: "lesson's videos cannot be more than {$this->lessonFileTypeMax}",
                data: $lessonDTO
            );
        }

        if ($lessonFilesDTO->audiosCount > $this->lessonFileTypeMax) {
            $this->throwLessonException(
                message: "lesson's audios cannot be more than {$this->lessonFileTypeMax}",
                data: $lessonDTO
            );
        }

        if ($lessonFilesDTO->filesCount > $this->lessonFileTypeMax) {
            $this->throwLessonException(
                message: "lesson's files cannot be more than {$this->lessonFileTypeMax}",
                data: $lessonDTO
            );
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
    
    public function updateLesson(LessonDTO $lessonDTO)
    {

        $lessonDTO = $this->setLessonAddedby($lessonDTO);

        $this->checkAccountOwnership($lessonDTO->addedby,$lessonDTO);

        $lesson = $this->createOrUpdateLesson($lessonDTO, 'update');

        $lessonDTO = $lessonDTO->withLesson($lesson);

        $lessonDTO = $this->setLessonOwnedby($lessonDTO);

        $this->checkLessonFiles($lesson, $lessonDTO);
        
        $this->createAttachments(
            $lesson,
            $lesson->addedby,
            $lessonDTO->attachments,
            true
        );

        $this->removeAttachments( 
            item: $lesson,
            account: (
                $lesson->addedby->accountType === 'facilitator' || 
                $lesson->addedby->accountType === 'professional'
            ) ? $lesson->addedby : null,
            facilitate: false,
            attachments: $lessonDTO->removedAttachments,
        );

        $lesson = $this->addFiles($lesson, $lessonDTO);

        $this->deleteFiles(
            files: $lessonDTO->removedFiles, 
            lesson: $lesson
        );

        $lesson = $this->createLinks($lesson,$lessonDTO->links);

        $this->editLinks($lessonDTO->editedLinks);

        $this->deleteLinks($lessonDTO->removedLinks);

        $lesson = $this->addMainLessonDetails($lesson, $lessonDTO);

        $this->notifySchoolAdmins($lesson, $lessonDTO, 'updated');

        $this->broadcastUpdate($lesson, $lessonDTO);

        $this->trackAdminActivity($lesson, $lessonDTO, __METHOD__);

        return $lesson;
    }

    private function trackAdminActivity
    (
        Lesson $lesson, 
        LessonDTO $lessonDTO, 
        $method
    )
    {
        if (!$lessonDTO->adminId) {
            return ;
        }

        if (!$lessonDTO->main) {
            return;
        }

        (new ActivityTrackService)->trackActivity(
            $lesson,
            $lesson->ownedby,
            $lesson->addedby,
            $method
        );
    }

    public function checkLessonAuthorization($lesson,$lessonDTO)
    {
        if (!$lessonDTO->main) {
            return;
        }
        
        if ($this->doesntHaveAuthorization($lesson,$lessonDTO->userId)) {
            return;
        }

        $this->throwLessonException(
            message: "You are not authorized to edit or delete the lesson with id {$lesson->id}"
        );
    }

    public function deleteLesson(LessonDTO $lessonDTO)
    {
        $lesson = $this->getLessonModel($lessonDTO);

        $this->checkLessonAuthorization($lesson,$lessonDTO);

        $this->trackAdminActivity($lesson, $lessonDTO, __METHOD__);

        if ($lessonDTO->action === 'undo') {
            return $this->changeState($lesson,'accepted');
        }

        if ($lessonDTO->main && $this->paymentMadeFor($lesson)) {
            return $this->changeState($lesson,'deleted');
        }

        $this->deleteLessonLinks($lesson);

        $this->deleteLessonFiles($lesson);
        
        $lesson->delete();
        
        $this->broadcastDelete($lesson, $lessonDTO);

        return null;
    }

    private function deleteLessonLinks
    (
        Lesson $lesson
    ) : Lesson
    {
        $lesson->links->each(function($link) {
            $link->delete();
        });
        return $lesson->refresh();
    }

    private function deleteLessonFiles
    (
        Lesson $lesson,
    ) : Lesson
    {
        FileService::deleteYourEduItemFiles(
            item: $lesson
        );

        return $lesson->refresh();
    }

    /**
     * this helps to determine which state to set when creating lesson
     */
    private function getState($account,$owner,$main)
    {
        if (!$main) return null;
        return $owner === 'school' && 
            ($account !== 'admin' && $account !== 'school') 
            ? 'PENDING' : 'ACCEPTED';
    }

    private function throwLessonException
    (
        string $message, 
        $data = null
    )
    {
        throw new LessonException(
            message: $message,
            data: $data
        );
    }

    private function createOrUpdateLesson
    (
        LessonDTO $lessonDTO,
        string $method
    ) : Lesson
    {
        $data = [
            'title' => $lessonDTO->title,
            'state' => $lessonDTO->state,
            'age_group' => $lessonDTO->state,
            'description' => $lessonDTO->description,
            'published_at' => $lessonDTO->publishedAt?->toDateTimeString(),
        ];

        $lesson = null;

        if ($method === 'create') {
            $lesson = $lessonDTO->addedby->addedLessons()
                ->create($data);
        }
        
        if ($method === 'update') {
            $lesson = $this->getLessonModel($lessonDTO);

            $this->checkLessonAuthorization($lesson,$lessonDTO);

            $lesson?->update($data);
        }
        
        if (is_null($lesson)) {
            $this->throwLessonException(
                message: "failed to {$method} lesson.",
                data: $lessonDTO
            );
        }
        
        return $lesson->refresh();
    }

    private function setLessonAddedby(LessonDTO $lessonDTO) : LessonDTO
    {
        if ($lessonDTO->addedby) {
            return $lessonDTO;
        }

        if (!$lessonDTO->main) {
            return $lessonDTO;
        }

        $addedby = getYourEduModel(
            $lessonDTO->account,
            $lessonDTO->accountId
        );

        if (is_null($addedby)) {
            $this->throwLessonException(
                message: "{$lessonDTO->account} not found with id {$lessonDTO->accountId}",
                data: $lessonDTO
            );
        }

        $lessonDTO = $lessonDTO->withAddedby($addedby);
        
        return $lessonDTO;
    }

    private function setLessonOwnedby(LessonDTO $lessonDTO) : LessonDTO
    {
        if ($lessonDTO->account === $lessonDTO->owner && $lessonDTO->accountId === $lessonDTO->ownerId) {
            $lessonDTO->ownedby = $lessonDTO->addedby;
        } else {
            $lessonDTO->ownedby = getYourEduModel($lessonDTO->owner,$lessonDTO->ownerId);
            if ($lessonDTO->main && is_null($lessonDTO->ownedby)) {
                $this->throwLessonException(
                    message: "{$lessonDTO->owner} not found with id {$lessonDTO->ownerId}",
                    data: $lessonDTO
                );
            }
        }  

        return $lessonDTO;
    }
    
    private function attachLessonToOwnedby
    (
        Lesson $lesson,
        LessonDTO $lessonDTO
    ) : Lesson
    {
        $lesson->ownedby()->associate($lessonDTO->ownedby);
        $lesson->save();

        return $lesson->refresh();
    }
    
    private function attachLessonToItem
    (
        Lesson $lesson,
        LessonDTO $lessonDTO
    ) : Lesson
    {
        $lesson->lessonable()->associate($lessonDTO->lessonable);
        $lesson->save();

        return $lesson->refresh();
    }

    private function addMainLessonDetails
    (
        Lesson $lesson,
        LessonDTO $lessonDTO
    ) : Lesson
    {
        
        if (!$lessonDTO->main) {
            return $lesson;
        }

        $lesson = $this->createMainAttachments(
            attachments: $lessonDTO->items,
            method: 'lessons',
            lesson: $lesson,
            activity: $lessonDTO->intro ? 'INTRO' : ($lessonDTO->free ? 
                'FREE' : null)
        );

        $lesson = $this->removeMainAttachments(
            attachments: $lessonDTO->removedItems,
            method: 'lessons',
            lesson: $lesson,
        );

        $this->setPayment(
            item: $lesson,
            addedby: $lesson->addedby,
            paymentType: $lessonDTO->type,
            paymentData: $lessonDTO->paymentData,
        );

        $this->removePayment(
            item: $lesson,
            paymentData: $lessonDTO->removedPaymentData,
        );

        $this->createAutoDiscussion(
            item: $lesson,
            itemData: $lessonDTO
        );

        return $lesson->refresh();
    }

    private function broadcastNewLesson($lesson, $lessonDTO)
    {
        if (!$lessonDTO->main) {
            return;
        }

        broadcast(new NewLessonEvent([
            'account' => $lessonDTO->owner,
            'accountId' => $lessonDTO->ownerId,
            'classes' => $lesson->classes->toArray(),
            'extracurriculums' => $lesson->extracurriculums->toArray(),
            'courseSections' => $lesson->courseSections->toArray(),
            'courses' => $lesson->courses()->hasOwner()->get()->toArray(),
            'lesson' => new LessonResource($lesson),
        ]))->toOthers();
    }

    private function notifySchoolAdmins
    (
        $lesson, 
        $lessonDTO, 
        $type = 'created'
    )
    {
        if (!$lessonDTO->main) {
            return;
        }

        if ($lessonDTO->ownedby?->accountType !== 'school') {
            return;
        }

        $userIds = array_filter($lessonDTO->ownedby->getAdminIds(),
            function($id) use ($lessonDTO){
                return (int) $id !== $lessonDTO->userId;
            }
        );

        $notification = $this->getSchoolNotification($lesson, $lessonDTO, $type);

        if (is_null($notification)) {
            return;
        }

        Notification::send(
            User::whereIn('id',$userIds)->get(), 
            $notification
        );
    }

    private function getSchoolNotification
    (
        Lesson $lesson,
        LessonDTO $lessonDTO,
        string $type
    )
    {
        $name = $lessonDTO->ownedby->company_name;

        if ($type === 'created') {                
            $notification = new LessonCreatedNotification(
                new UserAccountResource($lessonDTO->addedby),
                "created a lesson with the name: {$lesson->title}, 
                for {$name}. Please go to dashboard to approve or otherwise."
            );
        }

        if ($type === 'updated') {                
            $notification = new LessonUpdatedNotification(
                new UserAccountResource($lessonDTO->addedby),
                "updated a lesson with the name: {$lesson->title}, for {$name}."
            );
        }

        if ($type === 'deleted') {                
            $notification = new LessonDeletedNotification(
                new UserAccountResource($lessonDTO->addedby),
                "deleted a lesson with the name: {$lesson->title}, for {$name}."
            );
        }

        if (!isset($notification)) {
            return null;
        }
        
        return $notification;
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
                $attachable = getYourEduModel('class',$attachment->classId);
            }

            if (!isset($attachable)) {
                $attachable = getYourEduModel($attachment->type, $attachment->id);
            }
            
            if ($attachable->$method->where('id',$lesson->id)->count() > 0) {
                $attachable->$method()->detach($lesson->id);
            }
        }

        return $lesson->refresh();
    }

    private function getModel($account, $accountId)
    {
        $model = getYourEduModel($account, $accountId);
        if (is_null($model)) {
            throw new AccountNotFoundException("{$account} with id {$accountId} not found.");
        }

        return $model;
    }

    private function getLessonModel(LessonDTO $lessonDTO)
    {
        if ($lessonDTO->lesson) {
            return $lessonDTO->lesson;
        }

        $lesson = getYourEduModel('lesson', $lessonDTO->lessonId);
        if (is_null($lesson)) {
            throw new AccountNotFoundException("lesson with id {$lessonDTO->lessonId} not found.");
        }

        return $lesson;
    }

    private function createMainAttachments
    (
        array $attachments,
        $method,
        Lesson $lesson,
        $activity
    )
    {
        foreach ($attachments as $attachment) {

            if (property_exists($attachment,"classId")) {
                $attachable = getYourEduModel('class',$attachment->classId);
            }

            if (!isset($attachable)) {
                $attachable = $this->getModel($attachment->type, $attachment->id);
            }

            if ($activity === 'INTRO') {
                $this->checkForLessonIntro($attachable);
            }

            if ($this->alreadyHasLesson($attachable,$lesson)) {
                return $lesson;
            }

            if ($attachment->type === 'courseSection') {
                
                $attachable->$method()->attach($lesson->id,[
                    'lesson_number' => $attachable->lessons->last()?->lesson_number + 1
                ]);
                
                return $lesson->refresh();
            } 
            
            if (class_basename_lower($attachable) === 'class') {

                $attachable->$method()->attach($lesson->id,[
                    'activity'=>$activity,
                    'subject_id' => $attachment->id
                ]);

                return $lesson->refresh();
            } 
                
            $attachable->$method()->attach($lesson->id,[
                'activity' => $activity
            ]);
        }

        return $lesson->refresh();
    }

    private function alreadyHasLesson($item,$lesson)
    {
        if ($item->lessons->where('id',$lesson->id)->count() > 0) {
            return true;
        }
        return false;
    }

    private function checkForLessonIntro($item) 
    {
        if ($item->lessons()->where('activity','INTRO')->count() > 0) {
            $itemType = class_basename_lower($item);
            $this->throwLessonException("there is already an introductory lesson for {$itemType} with id {$item->id}");
        }
    }

    private function addFiles(Lesson $lesson,LessonDTO $lessonDTO,)
    {
        foreach ($lessonDTO->files as $file) {
            FileService::createAndAttachFiles(
                file: $file,
                account: $lesson->addedby,
                item: $lesson
            );
        }

        return $lesson->refresh();
    }

    private function createLinks($lesson, $links)
    {
        if (!is_array($links)) return $lesson;

        foreach ($links as $link) {
            $link->linkable = $lesson;
            $link->addedby = $lesson->addedby;
            LinkService::createLink($link);
        }

        return $lesson->refresh();
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
            FileService::deleteAndUnattachFiles($file,$lesson);
        }

        return $lesson->refresh();
    }

    private function broadcastUpdate($lesson, $lessonDTO)
    {
        if (!$lessonDTO->main) {
            return;
        }

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

    private function broadcastDelete($lesson, $lessonDTO)
    {
        if (!$lessonDTO->main) {
            return;
        }

        broadcast(new DeleteLessonEvent([
            'account' => class_basename_lower($lesson->ownedby),
            'accountId' => $lesson->ownedby->id,
            'classes' => $lesson->classes->toArray(),
            'courses' => $lesson->courses()->hasOwner()->get()->toArray(),
            'courseSections' => $lesson->courseSections->toArray(),
            'extracurriculums' => $lesson->extracurriculums->toArray(),
            'lessonId' => $lessonDTO->lessonId,
        ]))->toOthers();
    }

    private function paymentMadeFor($lesson)
    {
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