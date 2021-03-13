<?php

namespace App\Traits;

use App\Contracts\ItemDataContract;
use App\Exceptions\AccountNotFoundException;
use App\Exceptions\ClassCourseTraitException;
use App\Services\CourseService;
use App\Services\DiscussionService;
use App\Services\FeeService;
use App\Services\GradeService;
use App\Services\PriceService;
use App\Services\ProgramService;
use App\Services\SubjectService;
use App\Services\SubscriptionService;
use Illuminate\Support\Str;
use \Debugbar;

/**
 * this holds functions common to class and course services
 */
trait ClassCourseTrait
{
    private function doesntHaveAuthorization($item,$userId)
    {
        $ids = $item->ownedby ? $item->ownedby->authorizedIds() : [];

        if ($item::class === 'App\\YourEdu\\Lesson') {
            $ids[] = $item->addedby->user_id;
        }

        if (!in_array($userId, $ids)) {
            return false;
        }

        return true;
    }

    /**
     * this sets payment for items like courses, classes, extracurriculum, lessons, etc
     */
    private function setPayment($item,$addedby,$paymentType,$paymentData,$academicYears = [])
    {
        if ($paymentType == 'price') {
            foreach ($paymentData as $priceData) {
                PriceService::set($item,$priceData,$addedby);
            }
        } else if ($paymentType == 'fee') {
            foreach ($paymentData as $feeData) {
                FeeService::set(
                    $item,
                    [
                        'amount' => $feeData->amount,
                        'sections' => $feeData->sections,
                        'academicYears' => $academicYears
                    ],
                    $addedby
                );
            }
        } else if ($paymentType == 'subscription') {
            foreach ($paymentData as $subscriptionData) {
                SubscriptionService::set($item,$subscriptionData,$addedby);
            }
        }
    }

    private function removePayment($item,$paymentData)
    {
        if (!is_array($paymentData)) {
            return;
        }
        foreach ($paymentData as $data) {
            switch ($data->type) {
                case 'price':
                    PriceService::unset($item,$data->id);
                    break;
                
                case 'subscription':
                    SubscriptionService::unset($item,$data->id);
                    break;
            
                case 'fee':
                    FeeService::unset($item,$data->id);
                    break;
                    
                default:
                    # code...
                    break;
            }
        }
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
                        $item::class === 'App\\YourEdu\\Lesson' ? null : 'offer' //for lessons, subjects with offer will be add in the main attachment
                    );
                    if ($facilitate) {
                        CourseService::courseAttachItem($attachment->id,$account,'facilitate');
                    }
                    break;
                
                case 'subject':

                    SubjectService::subjectAttachItem(
                        $attachment->id,
                        $item,
                        $item::class === 'App\\YourEdu\\Lesson' ? null : 'offer' //for lessons, subjects with offer will be add in the main attachment
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
        if ($itemData->discussionData && $item->discussions->count() < 1) {
            $discussion = (new DiscussionService())->createDiscussion(
                $itemData->account,
                $itemData->accountId,
                $itemData->discussionData->title,
                $itemData->discussionData->preamble,
                $itemData->discussionData->restricted,
                $itemData->discussionData->type,
                $itemData->discussionData->allowed,
                $itemData->files,
                null
            );
            $discussion->discussionfor()->associate($item);
            $discussion->save();
        }
    }

    private function changeState($item,$state)
    {
        $item->update(['state' => Str::upper($state)]);
                
        $this->broadcastUpdate($item);

        return $item;
    }

    abstract private function usedByAnother($item);
    
    abstract private function paymentMadeFor($item);

    private function checkAccountOwnership($account,$dto)
    {
        if ($account->accountType === 'school') {
            $accountUserId = $account->owner_id;
        } else {
            $accountUserId = $account->user_id;
        }

        if ($accountUserId !== $dto->userId) {
            throw new AccountNotFoundException(
                message: "you do not own this account",
                data: $dto
            );
        }
    }

    /**
     *this is to help attach courses, extracurriculum to main items such as  
     *classes and programs
     */
    private function createMainAttachments($attachments,$method,$itemId,$userId,$activity = null)
    {
        if (is_array($attachments)) {
            foreach ($attachments as $attachment) {
                $actualItem = getYourEduModel($attachment->type,$attachment->id); //work in this,must be programs and classes
                // Debugbar::info($actualItem);
                $count = $actualItem->$method->where('id',$itemId)->count();
                if (!is_null($actualItem) &&
                    $count === 0) {    
                    if (is_null($activity) && !isOwnedBy($actualItem->ownedby,$userId)) { //used $activity because this will only be provided when called from lesson service
                        throw new ClassCourseTraitException("{$attachment->type} with id {$attachment->id} does not belong to you.");
                    }           
                    $this->mainAttachmentsAction(
                        actualItem: $actualItem,
                        itemId: $itemId,
                        method: $method,
                        action: 'attach',
                        activity: $activity
                    );
                } else if ($count && !is_null($activity)) {
                    if (!($activity === 'INTRO' && 
                        $actualItem->$method()->wherePivot('activity','INTRO')->count())) {
                        $actualItem->$method()->updateExistingPivot($itemId,[
                            'activity' => $activity
                        ]);
                    } else {
                        throw new ClassCourseTraitException("{$attachment->type} with id {$attachment->id} already has an intro lesson.");
                    }
                } else if (is_null($actualItem)) {
                    throw new AccountNotFoundException("{$attachment->type} with id {$attachment->id} was not found.");
                }
            }
        }
    }

    /**
     * this is to help remove courses or extracuriculum from items like
     * classes and programs
     */
    private function removeMainAttachments($attachments,$itemId,$method,)
    {
        if (is_array($attachments)) {
            foreach ($attachments as $attachment) {
                $actualItem = getYourEduModel($attachment->type,$attachment->id); //work in this,must be programs and classes
                if (!is_null($actualItem) &&
                    $actualItem->$method->where('id',$itemId)->count() > 0) {                
                    $this->mainAttachmentsAction(
                        actualItem: $actualItem,
                        itemId: $itemId,
                        method: $method,
                        action: 'detach',
                        activity: null
                    );
                }
            }
        }
    }

    private function mainAttachmentsAction($actualItem,$itemId,$method,$action,$activity)
    {
        // Debugbar::info($action); 
        $pivotArray = [];
        if ($method === 'classes' && $action === 'attach' && 
            $actualItem::class === 'App\\YourEdu\\Subject') {
            $pivotArray['activity'] = 'OFFER';
        } else if (!is_null($activity)) {
            $pivotArray['activity'] = Str::upper($activity);
        }
        $actualItem->$method()->$action($itemId,$pivotArray);
        $actualItem->save();
    }
}
