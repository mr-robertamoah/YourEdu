<?php

namespace App\Services;

use App\DTOs\ExtracurriculumDTO;
use App\Events\DeleteExtracurriculumEvent;
use App\Events\NewExtracurriculumEvent;
use App\Events\UpdateExtracurriculumEvent;
use App\Exceptions\ExtracurriculumException;
use App\Http\Resources\ExtracurriculumResource;
use App\Http\Resources\UserAccountResource;
use App\Notifications\ExtracurriculumCreatedNotification;
use App\Traits\DashboardItemServiceTrait;
use App\User;
use App\YourEdu\Extracurriculum;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class ExtracurriculumService 
{
    use DashboardItemServiceTrait;
    /**
     * create an extracurriculum
     * @return Extracurriculum
     */
    public function createExtracurriculum(ExtracurriculumDTO $extracurriculumDTO) : Extracurriculum
    {
        $extracurriculumDTO = $extracurriculumDTO->withAddedby(
            $this->getModel(
                $extracurriculumDTO->account,
                $extracurriculumDTO->accountId
            )
        );
        
        $this->checkAccountOwnership($extracurriculumDTO->addedby,$extracurriculumDTO);

        $extracurriculum = $extracurriculumDTO->addedby->addedExtracurriculums()->create([
            'name' => $extracurriculumDTO->name,
            'state' => $extracurriculumDTO->owner === 'school' && 
                ($extracurriculumDTO->account !== 'admin' && $extracurriculumDTO->account !== 'school') 
                ? 'PENDING' : 'ACCEPTED',
            'description' => $extracurriculumDTO->description,
        ]);

        if (is_null($extracurriculum)) {
            $this->throwExtracurriculumException(
                message: "extracurriculum creation failed",
                data: $extracurriculumDTO
            );
        }

        $extracurriculumDTO = $this->setDtoOwnedby($extracurriculumDTO);

        $extracurriculum = $this->updateOwnedby($extracurriculum, $extracurriculumDTO);

        $extracurriculumDTO->method = __METHOD__;
        $extracurriculum = $this->addMainExtracurriculumDetails(
            extracurriculum: $extracurriculum,
            extracurriculumDTO: $extracurriculumDTO,
        );

        $extracurriculumDTO->methodType = 'created';
        $this->notifySchoolAdmins($extracurriculum, $extracurriculumDTO);

        $this->broadcastExtracurriculum($extracurriculum, $extracurriculumDTO);

        return $extracurriculum;
    }

    private function throwExtracurriculumException
    (
        $message,
        $data = null
    )
    {
        throw new ExtracurriculumException(
            message: $message,
            data: $data
        );
    }

    private function broadcastExtracurriculum
    (
        $extracurriculum,
        $extracurriculumDTO,
    )
    {
        $event = $this->getEvent($extracurriculum, $extracurriculumDTO);

        if (is_null($event)) {
            return;
        }

        broadcast($event)->toOthers();
    }

    private function getEvent
    (
        $extracurriculum,
        $extracurriculumDTO,
    )
    {
        if ($extracurriculumDTO->methodType === 'created') {
            return new NewExtracurriculumEvent([
                'account' => $extracurriculumDTO->owner,
                'accountId' => $extracurriculumDTO->ownerId,
                'classes' => $extracurriculum->classes,
                'programs' => $extracurriculum->programs,
                'extracurriculum' => new ExtracurriculumResource($extracurriculum),
            ]);
        }

        if ($extracurriculumDTO->methodType === 'updated') {
            return new UpdateExtracurriculumEvent([
                'account' => class_basename_lower($extracurriculum->ownedby),
                'accountId' => $extracurriculum->ownedby->id,
                'extracurriculum' => new ExtracurriculumResource($extracurriculum),
                'classes' => $extracurriculum->classes,
                'programs' => $extracurriculum->programs,
            ]);
        }

        if ($extracurriculumDTO->methodType === 'deleted') {
            return new DeleteExtracurriculumEvent([
                'account' => class_basename_lower($extracurriculum->ownedby),
                'accountId' => $extracurriculum->ownedby->id,
                'classes' => $extracurriculum->classes,
                'programs' => $extracurriculum->programs,
                'extracurriculumId' => $extracurriculumDTO->extracurriculumId,
            ]);
        }

        return null;
    }

    private function notifySchoolAdmins
    (
        Extracurriculum $extracurriculum,
        ExtracurriculumDTO $extracurriculumDTO,
    )
    {
        if ($extracurriculumDTO->ownedby->accountType === 'school') {
        }
        $userIds = array_filter($extracurriculumDTO->ownedby->getAdminIds(),function($id) use ($extracurriculumDTO){
            return $id !== $extracurriculumDTO->userId;
        });

        $notification = $this->getNotification(
            $extracurriculum, $extracurriculumDTO
        );

        if (is_null($notification)) {
            return;
        }

        Notification::send(
            User::whereIn('id',$userIds)->get(), 
            $notification
        );
    }

    private function getNotification
    (
        $extracurriculum,
        $extracurriculumDTO,
    )
    {
        $name = $extracurriculumDTO->ownedby->company_name;

        if ($extracurriculumDTO->methodType === 'created') {
            return new ExtracurriculumCreatedNotification(
                new UserAccountResource($extracurriculumDTO->addedby),
                "created a extracurriculum with the name: {$extracurriculum->name}, 
                for {$name}. Please go to dashboard to approve or otherwise."
            );
        }

        return null;
    }

    private function addMainExtracurriculumDetails
    (
        Extracurriculum $extracurriculum,
        ExtracurriculumDTO $extracurriculumDTO,
    )
    {
        $this->createAttachments(
            $extracurriculum,
            $extracurriculumDTO->addedby,
            $extracurriculumDTO->attachments,
            $extracurriculumDTO->facilitate
        );

        $this->attachToItems(
            attachments: $extracurriculumDTO->items,
            attachable: $extracurriculum,
            dto: $extracurriculumDTO
        );

        $this->setPayment(
            item: $extracurriculum,
            addedby: $extracurriculumDTO->addedby,
            paymentType: $extracurriculumDTO->type,
            paymentData: $extracurriculumDTO->paymentData,
        );

        $extracurriculum = $this->createAutoDiscussion(
            item: $extracurriculum,
            itemData: $extracurriculumDTO,
        );

        $this->trackSchoolAdmin($extracurriculum, $extracurriculumDTO);

        $extracurriculum = $this->updateAccountExtracurriculumFacilitation(
            extracurriculum: $extracurriculum, 
            extracurriculumDTO: $extracurriculumDTO, 
        );

        return $extracurriculum;
    }

    private function updateAccountExtracurriculumFacilitation
    (
        $extracurriculum,
        $extracurriculumDTO,
    )
    {
        if ($extracurriculumDTO->account !== 'facilitator' && 
            $extracurriculumDTO->account !== 'professional') {
            return $extracurriculum;
        }

        if ($extracurriculumDTO->facilitate) {
            
            self::extracurriculumAttachItem(
                $extracurriculum->id,
                $extracurriculumDTO->addedby,
                'facilitate'
            );
            return $extracurriculum->refresh();
        } 

        self::extracurriculumUnattachItem(
            $extracurriculum->id,
            $extracurriculumDTO->addedby
        );

        return $extracurriculum->refresh();
    }
    
    public function getExtracurriculum($extracurriculumId)
    {
        $extracurriculum = $this->getModel('extracurriculum',$extracurriculumId);

        return $extracurriculum;
    }
    
    public function getExtracurriculums()
    {
        
    }
    
    public function updateExtracurriculum
    (
        ExtracurriculumDTO $extracurriculumDTO
    ) 
    {
        $extracurriculumDTO = $extracurriculumDTO->withAddedby(
            $this->getModel($extracurriculumDTO->account,$extracurriculumDTO->accountId)
        );
        
        $this->checkAccountOwnership($extracurriculumDTO->addedby,$extracurriculumDTO);
        
        $extracurriculum = $this->getModel('extracurriculum',$extracurriculumDTO->extracurriculumId);
        
        $this->checkExtracurriculumAuthorization($extracurriculum,$extracurriculumDTO);

        $extracurriculum->update([
            'name' => $extracurriculumDTO->name,
            'state' => $extracurriculumDTO->state && strlen($extracurriculumDTO->state) ?
                Str::upper($extracurriculumDTO->state) : "PENDING",
            'description' => $extracurriculumDTO->description,
        ]);

        $extracurriculumDTO->method = __METHOD__;
        $extracurriculum = $this->addMainExtracurriculumDetails(
            extracurriculum: $extracurriculum,
            extracurriculumDTO: $extracurriculumDTO,
        );
        
        $this->detachFromItems(
            attachments: $extracurriculumDTO->removedItems,
            attachable: $extracurriculum,
        );

        $this->removeAttachments( 
            item: $extracurriculum,
            account: ($extracurriculumDTO->account === 'facilitator' || $extracurriculumDTO->account === 'professional') ? 
                $extracurriculumDTO->addedby : null,
            facilitate: $extracurriculumDTO->facilitate,
            attachments: $extracurriculumDTO->removedAttachments,
        );

        $extracurriculum = $this->removePayment(
            item: $extracurriculum,
            paymentData: $extracurriculumDTO->removedPaymentData,
        );

        $extracurriculumDTO->methodType = 'updated';
        $this->broadcastExtracurriculum($extracurriculum, $extracurriculumDTO);

        return $extracurriculum;
    }

    public function checkExtracurriculumAuthorization
    (
        $extracurriculum,
        $extracurriculumDTO
    )
    {
        if ($this->hasAuthorization($extracurriculum,$extracurriculumDTO->userId)) {
            return;
        }
        
        $this->throwExtracurriculumException(
            message: "You are not authorized to edit or delete the extracurriculum with id {$extracurriculum->id}",
            data: $extracurriculumDTO
        );
    }

    public function deleteExtracurriculum(ExtracurriculumDTO $extracurriculumDTO)
    {
        $extracurriculum = $this->getModel('extracurriculum',$extracurriculumDTO->extracurriculumId);
        
        $this->checkExtracurriculumAuthorization($extracurriculum,$extracurriculumDTO);

        $this->trackSchoolAdmin($extracurriculum, $extracurriculumDTO);

        if ($extracurriculumDTO->action === 'undo') {
            return $this->changeState($extracurriculum,'accepted');
        } 

        if ($this->paymentMadeFor($extracurriculum) || $this->usedByAnotherItem($extracurriculum)) {
            return $this->changeState($extracurriculum,'deleted');
        } 

        $extracurriculumDTO->methodType = 'deleted';
        $this->broadcastExtracurriculum($extracurriculum, $extracurriculumDTO);

        $extracurriculum->delete();
        return null;
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
        if ($extracurriculum->hasPayments()) {
            return true;
        }

        return false;
    }

    private function usedByAnotherItem($extracurriculum)
    {
        if ($extracurriculum->isUsedByAnotherItem()) {
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
