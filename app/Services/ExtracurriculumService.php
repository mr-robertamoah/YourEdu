<?php

namespace App\Services;

use App\DTOs\ExtracurriculumData;
use App\Events\DeleteExtracurriculumEvent;
use App\Events\NewExtracurriculumEvent;
use App\Events\UpdateExtracurriculumEvent;
use App\Exceptions\AccountNotFoundException;
use App\Exceptions\ExtracurriculumException;
use App\Http\Resources\ExtracurriculumResource;
use App\Http\Resources\UserAccountResource;
use App\Notifications\ExtracurriculumCreatedNotification;
use App\Traits\ClassCourseTrait;
use App\User;
use App\YourEdu\Extracurriculum;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class ExtracurriculumService 
{
    use ClassCourseTrait;
    /**
     * create an extracurriculum
     * @return Extracurriculum
     */
    public function createExtracurriculum(ExtracurriculumData $extracurriculumData) : Extracurriculum
    {
        $mainAccount = getYourEduModel($extracurriculumData->account,$extracurriculumData->accountId);
        if (is_null($mainAccount)) {
            throw new AccountNotFoundException("{$extracurriculumData->account} not found with id {$extracurriculumData->accountId}");
        }

        if (!$this->checkAccountOwnership($mainAccount,$extracurriculumData->userId)) {
            throw new ExtracurriculumException("you do not own this account");
        }

        $extracurriculum = $mainAccount->addedExtracurriculums()->create([
            'name' => $extracurriculumData->name,
            'state' => $extracurriculumData->owner === 'school' && 
                ($extracurriculumData->account !== 'admin' && $extracurriculumData->account !== 'school') 
                ? 'PENDING' : 'ACCEPTED',
            'description' => $extracurriculumData->description,
        ]);

        if (is_null($extracurriculum)) {
            throw new ExtracurriculumException("extracurriculum creation failed");
        }

        if ($extracurriculumData->account === $extracurriculumData->owner && $extracurriculumData->accountId === $extracurriculumData->ownerId) {
            $mainOwner = $mainAccount;
        } else {
            $mainOwner = getYourEduModel($extracurriculumData->owner,$extracurriculumData->ownerId);
            if (is_null($mainOwner)) {
                throw new AccountNotFoundException("{$extracurriculumData->owner} not found with id {$extracurriculumData->ownerId}");
            }
        }        

        $extracurriculum->ownedby()->associate($mainOwner);
        $extracurriculum->save();

        $this->itemCreateUpdateMethodParts(
            item: $extracurriculum,
            mainAccount: $mainAccount,
            itemData: $extracurriculumData,
            method: __METHOD__
        );

        if ($extracurriculum->state === 'PENDING') { //it is only pending if it belongs to school and it is created by a non owner or non admin
            $userIds = array_filter($mainOwner->getAdminIds(),function($id) use ($extracurriculumData){
                return $id !== $extracurriculumData->userId;
            });
            $name = $mainOwner->name ?? $mainAccount->company_name;
            Notification::send(User::whereIn('id',$userIds)->get(), 
                new ExtracurriculumCreatedNotification(
                    new UserAccountResource($mainAccount),
                    "created a extracurriculum with the name: {$extracurriculum->name}, 
                    for {$name}. Please go to dashboard to approve or otherwise."
                )
            );
        }

        if ($extracurriculumData->owner === 'school') {
            broadcast(new NewExtracurriculumEvent([
                'account' => $extracurriculumData->owner,
                'accountId' => $extracurriculumData->ownerId,
                'classes' => $extracurriculum->classes,
                'programs' => $extracurriculum->programs,
                'extracurriculum' => new ExtracurriculumResource($extracurriculum),
            ]))->toOthers();
        }

        return $extracurriculum;
    }

    private function itemCreateUpdateMethodParts
    (
        $item,
        $mainAccount,
        ExtracurriculumData $itemData,
        $method
    )
    {
        //attachments like extracurriculums programs grades
        $this->createAttachments(
            $item,
            $mainAccount,
            $itemData->attachments,
            $itemData->facilitate
        );

        //for classes and programs to which we may attach extracurriculum
        $this->createMainAttachments(
            attachments: $itemData->classes,
            method: 'extracurriculums',
            itemId: $item->id,
            userId: $itemData->userId
        );

        //set payment information
        $this->setPayment(
            item: $item,
            addedby: $mainAccount,
            paymentType: $itemData->type,
            paymentData: $itemData->paymentData,
        );

        //create auto discussion 
        $this->createAutoDiscussion(
            item: $item,
            itemData: $itemData,
        );

        //track school activities
        if ($itemData->account === 'admin') {
            (new ActivityTrackService())->createActivityTrack(
                $item,$item->ownedby,$mainAccount,$method
            );
        } else if ($itemData->account === 'facilitator' || $itemData->account === 'professional') {
            //update course relations
            if ($itemData->facilitate) { //facilitate
                self::extracurriculumAttachItem($item->id,$mainAccount,'facilitate');
            } else {
                self::extracurriculumUnattachItem($item->id,$mainAccount);
            }
        }
    }
    
    public function getExtracurriculum($extracurriculumId)
    {
        $extracurriculum = getYourEduModel('extracurriculum',$extracurriculumId);
        if (is_null($extracurriculum)) {
            throw new AccountNotFoundException("extracurriculum not found with id {$extracurriculumId}");
        }

        return $extracurriculum;
    }
    
    public function getExtracurriculums()
    {
        
    }
    
    public function updateExtracurriculum (ExtracurriculumData $extracurriculumData) 
    {
        //check account
        $mainAccount = getYourEduModel($extracurriculumData->account,$extracurriculumData->accountId);
        if (is_null($mainAccount)) {
            throw new AccountNotFoundException("$extracurriculumData->account not found with id {$extracurriculumData->accountId}");
        }

        if (!$this->checkAccountOwnership($mainAccount,$extracurriculumData->userId)) {
            throw new ExtracurriculumException("you do not own this account");
        }
        //check extracurriculum
        $extracurriculum = getYourEduModel('extracurriculum',$extracurriculumData->extracurriculumId);
        if (is_null($extracurriculum)) {
            throw new AccountNotFoundException("extracurriculum not found with id {$extracurriculumData->extracurriculumId}");
        }

        //check authorization
        $this->checkExtracurriculumAuthorization($extracurriculum,$extracurriculumData->userId);

        //update extracurriculum attributes
        $extracurriculum->update([
            'name' => $extracurriculumData->name,
            'state' => Str::upper($extracurriculumData->state),
            'description' => $extracurriculumData->description,
        ]);

        //update extracurriculum relations

        $this->itemCreateUpdateMethodParts(
            item: $extracurriculum,
            mainAccount: $mainAccount,
            itemData: $extracurriculumData,
            method: __METHOD__
        );
        
        //for classes and programs from which we may detach extracurriculum
        $this->removeMainAttachments(
            attachments: $extracurriculumData->removedClasses,
            method: 'extracurriculums',
            itemId: $extracurriculum->id,
        );

        $this->removeAttachments( //remove attachments
            item: $extracurriculum,
            account: ($extracurriculumData->account === 'facilitator' || $extracurriculumData->account === 'professional') ? $mainAccount : null,
            facilitate: $extracurriculumData->facilitate,
            attachments: $extracurriculumData->removedAttachments,
        );

        //set payment information
        $this->removePayment(
            item: $extracurriculum,
            paymentData: $extracurriculumData->removedPaymentData,
        );

        //broadcast
        $this->broadcastUpdate($extracurriculum);

        return $extracurriculum;
    }

    public function checkExtracurriculumAuthorization($extracurriculum,$userId)
    {
        if (!$this->checkAuthorization($extracurriculum,$userId)) {
            throw new ExtracurriculumException("You are not authorized to edit or delete the extracurriculum with id {$extracurriculum->id}");
        }
    }

    public function deleteExtracurriculum(ExtracurriculumData $extracurriculumData)
    {
        $extracurriculum = getYourEduModel('extracurriculum',$extracurriculumData->extracurriculumId);
        if (is_null($extracurriculum)) {
            throw new AccountNotFoundException("extracurriculum not found with id {$extracurriculumData->extracurriculumId}");
        }

        $this->checkExtracurriculumAuthorization($extracurriculum,$extracurriculumData->userId);

        if ($extracurriculumData->adminId) {
            $admin = getYourEduModel('admin',$extracurriculumData->adminId);
            (new ActivityTrackService())->createActivityTrack(
                $extracurriculum,$extracurriculum->ownedby,$admin,__METHOD__
            );
        }

        if ($extracurriculumData->action === 'undo') {
            return $this->changeState($extracurriculum,'accepted');
        } else if($extracurriculumData->action === 'delete') {
            //check if someone has subsribed or paid or used by a program
            if ($this->paymentMadeFor($extracurriculum) || $this->usedByAnother($extracurriculum)) {
                return $this->changeState($extracurriculum,'deleted');
            } else {
                
                broadcast(new DeleteExtracurriculumEvent([
                    'account' => class_basename_lower($extracurriculum->ownedby),
                    'accountId' => $extracurriculum->ownedby->id,
                    'classes' => $extracurriculum->classes,
                    'programs' => $extracurriculum->programs,
                    'extracurriculumId' => $extracurriculumData->extracurriculumId,
                ]))->toOthers();

                $extracurriculum->delete();
                return null;
            }
        }
    }

    private function broadcastUpdate($extracurriculum)
    {
        broadcast(new UpdateExtracurriculumEvent([
            'account' => class_basename_lower($extracurriculum->ownedby),
            'accountId' => $extracurriculum->ownedby->id,
            'extracurriculum' => new ExtracurriculumResource($extracurriculum),
            'classes' => $extracurriculum->classes,
            'programs' => $extracurriculum->programs,
        ]))->toOthers();
    }

    private function paymentMadeFor($extracurriculum)
    {
        if (
            $extracurriculum->whereHas('payments')
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

    private function usedByAnother($extracurriculum)
    {
        if ($extracurriculum->programs->whereNotNull('ownedby_type')->first() ||
            $extracurriculum->classes->whereNotNull('ownedby_type')->first()) {
            return true;
        }
        return false;
    }

    public static function extracurriculumAttachItem($extracurriculumId,$item,$activity)
    {
        if (is_null(
            $item->extracurriculums->where('id',$extracurriculumId)->first()
        )) {
            $item->extracurriculums()->attach($extracurriculumId,['activity' => Str::upper($activity)]);
            $item->save();
        }
    }

    public static function extracurriculumUnattachItem($extracurriculumId,$item)
    {
        if (!is_null(
            $item->extracurriculums->where('id',$extracurriculumId)->first()
        )) {
            $item->extracurriculums()->detach($extracurriculumId);
            $item->save();
        }
    }
}
