<?php

namespace App\Traits;

use App\Contracts\ItemDataContract;
use App\DTOs\ActivityTrackDTO;
use App\DTOs\DiscussionDTO;
use App\DTOs\PaymentDTO;
use App\Exceptions\DashboardItemServiceTraitException;
use App\Services\ActivityTrackService;
use App\Services\CourseService;
use App\Services\DiscussionService;
use App\Services\GradeService;
use App\Services\PaymentService;
use App\Services\ProgramService;
use App\Services\SubjectService;
use App\YourEdu\Admin;
use App\YourEdu\PostAttachment;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * this holds functions common to class and course services
 */
trait DashboardItemServiceTrait
{
    use ServiceTrait;

    private function setDtoOwnedby
    (
        $dto,
        Model $model = null
    )
    {
        if ($dto->account === $dto->owner && $dto->accountId === $dto->ownerId) {
            return $dto->withOwnedby(
                $dto->addedby
            );
        } 

        if ($model) {
            return $dto->withOwnedby(
                $model->ownedby
            );
        }

        return $dto->withOwnedby(
            $this->getModel(
                $dto->owner,
                $dto->ownerId
            )
        );
    }

    private function updateOwnedby
    (
        $model,
        $dto
    )
    {
        $model->ownedby()->associate($dto->ownedby);
        $model->save();

        return $model;
    }

    private function doesntHaveAuthorization($item,$userId)
    {
        $ids = $item->ownedby ? $item->ownedby->getAuthorizedIds() : [];

        array_push($ids, ...$item->getAuthorizedUserIds(onlyMain: true));

        if ($item::class === 'App\\YourEdu\\Lesson') {
            $ids[] = $item->addedby->user_id;
        }

        if (in_array($userId, $ids)) {
            return false;
        }

        return true;
    }

    private function hasAuthorization($item, $userId)
    {
        return !$this->doesntHaveAuthorization($item, $userId);
    }

    private function trackSchoolAdmin
    (
        $item,
        $dto,
    )
    {
        if (!$dto->adminId) {
            return;
        }

        $admin = $this->getModel('admin',$dto->adminId);
        
        (new ActivityTrackService())->trackActivity(
            ActivityTrackDTO::createFromData(
                activity: $item,
                activityfor: $item->ownedby,
                performedby: $admin,
                action: $dto->method
            )
        );
    }

    /**
     * this sets payment for items like courses, classes, extracurriculum, lessons, etc
     */
    private function setPayment
    (
        PaymentDTO $paymentDTO
    )
    {
        if (is_null($paymentDTO)) {
            return;
        }
        PaymentService::setMultiplePaymentOnItemBasedOnPaymentType($paymentDTO);

        return $paymentDTO->dashboardItem?->refresh();
    }

    private function removePayment(PaymentDTO $paymentDTO)
    {
        PaymentService::unsetMultiplePaymentFromItemBasedOnIndividualTypes($paymentDTO);
    }

    /**
     * for attaching things like courses, programs, subjects, etc 
     * to items like course, classes, extracurriculum
     */
    private function createAttachments
    (
        $item,
        $account,
        $attachments,
        $facilitate
    )
    {
        if (!$attachments || !is_array($attachments)) {
            return;
        }
        foreach ($attachments as $attachment) {
            switch ($attachment->type) {
                case 'grade':
                    GradeService::gradeAttachItem($attachment->id,$item);
                    if ($facilitate) {
                        GradeService::gradeAttachItem($attachment->id,$account);
                    }
                    break;
                
                case 'program':
                    ProgramService::programAttachItem($attachment->id,$item,'for');
                    if ($facilitate) {
                        ProgramService::programAttachItem($attachment->id,$account,'facilitate');
                    }
                    break;
                
                case 'course':
                    CourseService::courseAttachItem(
                        $attachment->id,
                        $item,
                        $item::class === 'App\\YourEdu\\Lesson' ? null : 'offer'
                    );
                    if ($facilitate) {
                        CourseService::courseAttachItem($attachment->id,$account,'facilitate');
                    }
                    break;
                
                case 'subject':

                    SubjectService::subjectAttachItem(
                        $attachment->id,
                        $item,
                        $item::class === 'App\\YourEdu\\Lesson' ? null : 'offer'
                    );
                    if ($facilitate) {
                        SubjectService::subjectAttachItem($attachment->id,$account,'facilitate');
                    }
                    break;
                
                default:
                    
                    break;
            }
        }
    }

    /**
     * for unattaching things like courses, programs, subjects, etc 
     * from items like course, classes, extracurriculum
     */
    private function removeAttachments
    (
        $item, //course, class, extracurriculum, lesson
        $account, //facilitator, professional
        $attachments,
        $facilitate
    )
    {
        if (!$attachments || !is_array($attachments)) {
            return;
        }
        foreach ($attachments as $attachment) {
            switch ($attachment->type) {
                case 'grade':
                    GradeService::gradeUnattachItem($attachment->id,$item);
                    if (!is_null($account) && !$facilitate) {
                        GradeService::gradeUnattachItem($attachment->id,$account);
                    }
                    break;
                
                case 'program':
                    ProgramService::programUnattachItem($attachment->id,$item,'for');
                    if (!is_null($account) && !$facilitate) {
                        ProgramService::programUnattachItem($attachment->id,$account,'facilitate');
                    }
                    break;
                
                case 'course':
                    CourseService::courseUnattachItem($attachment->id,$item,'offer');
                    if (!is_null($account) && !$facilitate) {
                        CourseService::courseUnattachItem($attachment->id,$account,'facilitate');
                    }
                    break;
                
                default:
                    
                    break;
            }
        }
    }

    /**
     * automatically create discussion for a course, class, extracurriculum, lesson, etc
     */
    private function createAutoDiscussion
    (
        $item,
        ItemDataContract $itemData,
    )
    {
        if ($item->hasDiscussion()) {
            return $item;
        }

        if (!$itemData->discussionData) {
            return $item;
        }

        $discussion = (new DiscussionService())->createDiscussion(
            $itemData->account,
            $itemData->accountId,
            $itemData->discussionData->title,
            $itemData->discussionData->preamble,
            $itemData->discussionData->restricted ?? false,
            $itemData->discussionData->type ?? 'PRIVATE',
            $itemData->discussionData->allowed ?? 'ALL',
            $itemData->discussionFiles,
            null
        );
        
        $item->discussions()->save($discussion);

        return $item->refresh();
    }

    private function deleteDiscussion($item, $dto)
    {
        if ($item->doesntHaveDiscussion()) {
            return $item;
        }

        (new DiscussionService)->deleteDiscussion(
            DiscussionDTO::createFromData(
                discussionId: $item->discussion()->id,
                userId: $dto->userId
            )
        );

        return $item->refresh();
    }

    private function changeState($item,$state)
    {
        $item->update(['state' => Str::upper($state)]);
                
        $this->broadcastUpdate($item);

        return $item;
    }
    
    abstract private function paymentMadeFor($item);

    private function checkAccountOwnership($account,$dto)
    {
        if ($account->accountType === 'school') {
            $accountUserId = $account->owner_id;
        } else {
            $accountUserId = $account->user_id;
        }

        if ($accountUserId !== $dto->userId) {
            $this->throwDashboardItemServiceTraitException(
                message: "you do not own this account",
                data: $dto
            );
        }
    }

    private function checkItemOwnership
    (
        $attachment,
        $ownedby,
        $userId
    )
    {
        if (isOwnedBy($ownedby,$userId)) { 
            return;
        }
        
        $this->throwDashboardItemServiceTraitException("{$attachment->type} with id {$attachment->id} does not belong to you.");
    }

    /**
     *this is to help attach courses, extracurriculum to main items such as  
     *classes and programs
     */
    private function attachToItems
    (
        $attachments,
        $attachable,
        $dto,
        $activity = null,
    )
    {
        if (!is_array($attachments)) {
            return;
        }
        
        $method = $this->getAttachOrDetachMethod($attachable);
        $attachedItems = new Collection();

        foreach ($attachments as $attachment) {
            $actualItem = $this->getModel($attachment->type,$attachment->id);

            if ($actualItem->$method->where('id',$attachable->id)->count()) {
                return;
            } 

            if (method_exists($actualItem, 'getAuthorizedUserIds') && 
                !in_array($dto->userId, $actualItem->getAuthorizedUserIds(onlyMain: true))) {
                $this->throwDashboardItemServiceTraitException(
                    message: "you are not authorized to attach {$attachment->type} with id {$attachment->id} does not belong to you.",
                    data: $dto
                );
            }

            $attachedItems->push($this->attachItem(
                actualItem: $actualItem,
                attachable: $attachable,
                activity: $activity,
                method: $method
            ));
        }

        return $attachedItems;
    }

    private function checkAttachmentCreatorAccountType($dto)
    {
        if (in_array($dto->account, PostAttachment::ATTACHMENTCREATORACCOUNTTYPE)) {
            return;
        }

        $this->throwDashboardItemServiceTraitException(
            message: "{$dto->account} can only create an alias of a subject",
            data: $dto
        );
    }

    private function checkAttachmentAuthorization($item, $dto)
    {
        if ($item->addedby->user_id === $dto->userId) {
            return;
        }

        if (Admin::whereYourEduAdminByUserId($dto->userId)->count()) {
            return;
        }

        $this->throwDashboardItemServiceTraitException(
            message: 'you cannot delete an item you did not create',
            data: $dto
        );
    }

    private function throwDashboardItemServiceTraitException
    (
        $message,
        $data = null
    )
    {
        throw new DashboardItemServiceTraitException(
            message: $message,
            data: $data
        );
    }

    private function getAttachOrDetachMethod($attachable)
    {
        $type = class_basename_lower($attachable);

        $prepend = 's';

        if ($type === 'class') {
            $prepend = 'es';
        }

        return  "{$type}{$prepend}";
    }

    /**
     * this is to help remove courses or extracuriculum from items like
     * classes and programs
     */
    private function detachFromItems($attachments,$attachable)
    {
        if (!is_array($attachments)) {
            return;
        }

        $method = $this->getAttachOrDetachMethod($attachable);
        $detachedItems = new Collection();

        foreach ($attachments as $attachment) {
            $actualItem = getYourEduModel($attachment->type,$attachment->id); //work in this,must be programs and classes
            
            if (is_null($actualItem)) { 
                continue;               
            }

            $detachedItems->push($this->detachItem(
                actualItem: $actualItem,
                attachable: $attachable,
                method: $method
            ));
        }

        return $detachedItems;
    }

    private function attachItem($actualItem, $attachable, $method, $activity)
    {
        $pivotArray = [];
        if ($method === 'classes' &&
            class_basename_lower($actualItem) === 'subject') {
            $method = 'subjectClasses';
            $pivotArray['activity'] = 'OFFER';
        } 
        
        if (!is_null($activity)) {
            $pivotArray['activity'] = Str::upper($activity);
        }

        $actualItem->$method()->attach($attachable->id,$pivotArray);
        $actualItem->save();
        return $actualItem->refresh();
    }

    private function detachItem($actualItem, $attachable, $method)
    {
        if ($actualItem->$method->where('id',$attachable->id)->count() === 0) {
            return;
        }

        if ($method === 'classes' &&
            class_basename_lower($actualItem) === 'subject') {
            $method = 'subjectClasses';
        }

        $actualItem->$method()->detach($attachable->id);
        $actualItem->save();
        return $actualItem->refresh();
    }
}
