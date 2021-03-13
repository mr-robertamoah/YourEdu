<?php

namespace App\Services;

use App\DTOs\ExtracurriculumDTO;
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
    public function createExtracurriculum(ExtracurriculumDTO $extracurriculumDTO) : Extracurriculum
    {
        $mainAccount = getYourEduModel($extracurriculumDTO->account,$extracurriculumDTO->accountId);
        if (is_null($mainAccount)) {
            throw new AccountNotFoundException("{$extracurriculumDTO->account} not found with id {$extracurriculumDTO->accountId}");
        }

        $this->checkAccountOwnership($mainAccount,$extracurriculumDTO);

        $extracurriculum = $mainAccount->addedExtracurriculums()->create([
            'name' => $extracurriculumDTO->name,
            'state' => $extracurriculumDTO->owner === 'school' && 
                ($extracurriculumDTO->account !== 'admin' && $extracurriculumDTO->account !== 'school') 
                ? 'PENDING' : 'ACCEPTED',
            'description' => $extracurriculumDTO->description,
        ]);

        if (is_null($extracurriculum)) {
            throw new ExtracurriculumException("extracurriculum creation failed");
        }

        if ($extracurriculumDTO->account === $extracurriculumDTO->owner && $extracurriculumDTO->accountId === $extracurriculumDTO->ownerId) {
            $mainOwner = $mainAccount;
        } else {
            $mainOwner = getYourEduModel($extracurriculumDTO->owner,$extracurriculumDTO->ownerId);
            if (is_null($mainOwner)) {
                throw new AccountNotFoundException("{$extracurriculumDTO->owner} not found with id {$extracurriculumDTO->ownerId}");
            }
        }        

        $extracurriculum->ownedby()->associate($mainOwner);
        $extracurriculum->save();

        $this->itemCreateUpdateMethodParts(
            item: $extracurriculum,
            mainAccount: $mainAccount,
            itemData: $extracurriculumDTO,
            method: __METHOD__
        );

        if ($extracurriculum->state === 'PENDING') { //it is only pending if it belongs to school and it is created by a non owner or non admin
            $userIds = array_filter($mainOwner->getAdminIds(),function($id) use ($extracurriculumDTO){
                return $id !== $extracurriculumDTO->userId;
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

        if ($extracurriculumDTO->owner === 'school') {
            broadcast(new NewExtracurriculumEvent([
                'account' => $extracurriculumDTO->owner,
                'accountId' => $extracurriculumDTO->ownerId,
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
        ExtracurriculumDTO $itemData,
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
            (new ActivityTrackService())->trackActivity(
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
    
    public function updateExtracurriculum (ExtracurriculumDTO $extracurriculumDTO) 
    {
        //check account
        $mainAccount = getYourEduModel($extracurriculumDTO->account,$extracurriculumDTO->accountId);
        if (is_null($mainAccount)) {
            throw new AccountNotFoundException("$extracurriculumDTO->account not found with id {$extracurriculumDTO->accountId}");
        }

        $this->checkAccountOwnership($mainAccount,$extracurriculumDTO);
        
        //check extracurriculum
        $extracurriculum = getYourEduModel('extracurriculum',$extracurriculumDTO->extracurriculumId);
        if (is_null($extracurriculum)) {
            throw new AccountNotFoundException("extracurriculum not found with id {$extracurriculumDTO->extracurriculumId}");
        }

        //check authorization
        $this->checkExtracurriculumAuthorization($extracurriculum,$extracurriculumDTO->userId);

        //update extracurriculum attributes
        $extracurriculum->update([
            'name' => $extracurriculumDTO->name,
            'state' => Str::upper($extracurriculumDTO->state),
            'description' => $extracurriculumDTO->description,
        ]);

        //update extracurriculum relations

        $this->itemCreateUpdateMethodParts(
            item: $extracurriculum,
            mainAccount: $mainAccount,
            itemData: $extracurriculumDTO,
            method: __METHOD__
        );
        
        //for classes and programs from which we may detach extracurriculum
        $this->removeMainAttachments(
            attachments: $extracurriculumDTO->removedClasses,
            method: 'extracurriculums',
            itemId: $extracurriculum->id,
        );

        $this->removeAttachments( //remove attachments
            item: $extracurriculum,
            account: ($extracurriculumDTO->account === 'facilitator' || $extracurriculumDTO->account === 'professional') ? $mainAccount : null,
            facilitate: $extracurriculumDTO->facilitate,
            attachments: $extracurriculumDTO->removedAttachments,
        );

        //set payment information
        $this->removePayment(
            item: $extracurriculum,
            paymentData: $extracurriculumDTO->removedPaymentData,
        );

        //broadcast
        $this->broadcastUpdate($extracurriculum);

        return $extracurriculum;
    }

    public function checkExtracurriculumAuthorization($extracurriculum,$userId)
    {
        if (!$this->doesntHaveAuthorization($extracurriculum,$userId)) {
            throw new ExtracurriculumException("You are not authorized to edit or delete the extracurriculum with id {$extracurriculum->id}");
        }
    }

    public function deleteExtracurriculum(ExtracurriculumDTO $extracurriculumDTO)
    {
        $extracurriculum = getYourEduModel('extracurriculum',$extracurriculumDTO->extracurriculumId);
        if (is_null($extracurriculum)) {
            throw new AccountNotFoundException("extracurriculum not found with id {$extracurriculumDTO->extracurriculumId}");
        }

        $this->checkExtracurriculumAuthorization($extracurriculum,$extracurriculumDTO->userId);

        if ($extracurriculumDTO->adminId) {
            $admin = getYourEduModel('admin',$extracurriculumDTO->adminId);
            (new ActivityTrackService())->trackActivity(
                $extracurriculum,$extracurriculum->ownedby,$admin,__METHOD__
            );
        }

        if ($extracurriculumDTO->action === 'undo') {
            return $this->changeState($extracurriculum,'accepted');
        } else if($extracurriculumDTO->action === 'delete') {
            //check if someone has subsribed or paid or used by a program
            if ($this->paymentMadeFor($extracurriculum) || $this->usedByAnother($extracurriculum)) {
                return $this->changeState($extracurriculum,'deleted');
            } else {
                
                broadcast(new DeleteExtracurriculumEvent([
                    'account' => class_basename_lower($extracurriculum->ownedby),
                    'accountId' => $extracurriculum->ownedby->id,
                    'classes' => $extracurriculum->classes,
                    'programs' => $extracurriculum->programs,
                    'extracurriculumId' => $extracurriculumDTO->extracurriculumId,
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
