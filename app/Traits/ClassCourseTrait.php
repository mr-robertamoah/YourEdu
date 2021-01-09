<?php

namespace App\Traits;

use App\Services\CourseService;
use App\Services\DiscussionService;
use App\Services\FeeService;
use App\Services\GradeService;
use App\Services\PriceService;
use App\Services\ProgramService;
use App\Services\SubscriptionService;
use Illuminate\Support\Str;

/**
 * this holds functions common to class and course services
 */
trait ClassCourseTrait
{
    private function checkAuthorization($class,$userId)
    {
        $account = getAccountString($class->ownedby);

        if ($account === 'school') {
            if (in_array($userId,getAdminIds($class->ownedby)) || 
                $class->ownedby->owner_id === $userId) {
                return true;
            } else {
                return false;
            }
        } else {
            if ($class->ownedby->user_id === $userId) {
                return true;
            } else {
                return false;
            }
        }
    }

    /**
     * this sets payment for items like courses, classes, extracurriculum, lessons, etc
     */
    private function setPayment($item,$addedby,$paymentType,$paymentData,$feeableData = [])
    {
        if ($paymentType == 'price') {
            foreach ($paymentData as $priceData) {
                PriceService::setPrice($item,$priceData,$addedby);
            }
        } else if ($paymentType == 'fee') {
            FeeService::setFee($item,$paymentData,$addedby,$feeableData);
        } else if ($paymentType == 'subscription') {
            foreach ($paymentData as $subscriptionData) {
                SubscriptionService::setSubscription($item,$subscriptionData,$addedby);
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
                    CourseService::courseAttachItem($attachment->id,$item,'offer');
                    if ($facilitate) {
                        CourseService::courseAttachItem($attachment->id,$account,'facilitate');
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
        $account,
        $accountId,
        $discussionData,
        $files
    )
    {
        if ($discussionData) {
            $discussion = (new DiscussionService())->createDiscussion(
                $account,
                $accountId,
                $discussionData->title,
                $discussionData->preamble,
                $discussionData->restricted,
                $discussionData->type,
                $discussionData->allowed,
                $files,
                null
            );
            $discussion->discussionfor()->associate($item);
            $discussion->save();
        }
    }

    private function changeState($ownedby,$classes,$item,$state)
    {
        $item->update(['state' => Str::upper($state)]);
                
        $this->broadcastUpdate($ownedby,$classes ?? [],$item);

        return $item;
    }

    abstract private function usedByAnother($item);
    
    abstract private function paymentMadeFor($item);
}
