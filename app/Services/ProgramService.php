<?php

namespace App\Services;

use App\DTOs\AttachmentDTO;
use App\DTOs\ProgramDTO;
use App\Events\DeleteProgramEvent;
use App\Events\NewProgramEvent;
use App\Events\UpdateProgramEvent;
use App\Exceptions\AccountNotFoundException;
use App\Exceptions\ProgramException;
use App\Http\Resources\DashboardProgramResource;
use App\Http\Resources\ProgramResource;
use App\Http\Resources\UserAccountResource;
use App\Notifications\ProgramCreatedNotification;
use App\Traits\DashboardItemServiceTrait;
use App\User;
use App\YourEdu\Programmable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class ProgramService
{
    use DashboardItemServiceTrait;
    
    public function createProgramAsAttachment(ProgramDTO $programDTO)
    {
        try { 
            DB::beginTransaction();

            $this->checkAttachmentCreatorAccountType($programDTO);
            
            $account = $this->getModel($programDTO->account, $programDTO->accountId);

            $program = (new AttachmentService())->createAttachmentWithAccount(
                AttachmentDTO::createFromData(
                    type: 'program',
                    name: $programDTO->name,
                    description: $programDTO->description,
                    rationale: $programDTO->rationale,
                    aliases: $programDTO->aliases
                )->withAddedby($account)
            );

            if (is_null($program)) {
                $this->throwProgramException(
                    message: 'program was not created',
                    data: $programDTO
                );
            }

            return $program;
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }

    public function createProgramAttachmentAlias(ProgramDTO $programDTO)
    {
        try { 
            DB::beginTransaction();
            $program = $this->getModel('program',$programDTO->programId);
            
            $programDTO = $programDTO->withAddedby(
                $this->getModel($programDTO->account,$programDTO->accountId)
            );
            
            $alias = (new AttachmentService())->createAttachmentAlias(
                $programDTO->addedby,
                $program,
                $programDTO->name,
                $programDTO->description
            );

            if (is_null($alias)) {
                $this->throwProgramException(
                    message: 'alias was not created',
                    data: $programDTO
                );
            }

            return $program;
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }

    public function deleteProgramAsAttachment(ProgramDTO $programDTO)
    {
        $program = $this->getModel('program',$programDTO->programId);

        $this->checkAttachmentAuthorization($program, $programDTO);

        $deletionStatus = $program->delete();

        if ($deletionStatus) {
            return;
        }
        
        $this->throwProgramException(
            message:"deletion of the program, with name: {$program->name}, failed",
            data: $programDTO
        );
    }

    public static function programAttachItem($programId,$item,$activity)
    {
        ray($item)->green();
        if (is_null(
            $item->programs->where('id',$programId)->first()
        )) {
            $item->programs()->attach($programId,['activity' => Str::upper($activity)]);
            $item->save();
        }
    }

    public static function programUnattachItem($programId,$item)
    {
        if (!is_null(
            $item->programs->where('id',$programId)->first()
        )) {
            $item->programs()->detach($programId);
            $item->save();
        }
    }

    public function createProgram(ProgramDTO $programDTO)
    {
        $programDTO = $programDTO->withAddedby(
            $this->getModel($programDTO->account,$programDTO->accountId)
        );
        
        $this->checkAccountOwnership($programDTO->addedby,$programDTO);

        $program = $programDTO->addedby->addedPrograms()->create([
            'name' => $programDTO->name,
            'state' => $programDTO->owner === 'school' && 
                ($programDTO->account !== 'admin' && $programDTO->account !== 'school') 
                ? 'PENDING' : 'ACCEPTED',
            'description' => $programDTO->description,
        ]);

        if (is_null($program)) {
            $this->throwProgramException("program creation failed");
        }

        $programDTO = $programDTO->withOwnedby(
            $this->getProgramOwnedby($program, $programDTO)
        );   

        $program->ownedby()->associate($programDTO->ownedby);
        $program->save();

        $programDTO->method = __METHOD__;
        $this->addMainProgramDetails(
            $program,
            $programDTO,
        );
        
        $programDTO->methodType = 'created';

        $this->notifySchoolAdmins($program, $programDTO);

        $this->broadCastProgram($program, $programDTO);

        return $program;
    }

    private function getProgramOwnedby
    (
        $program,
        $programDTO
    )
    {
        if ($program->ownedby) {
            return $program->ownedby;
        }

        if ($programDTO->account === $programDTO->owner && $programDTO->accountId === $programDTO->ownerId) {
            return $programDTO->addedby;
        }
        
        return $this->getModel($programDTO->owner,$programDTO->ownerId);
    }

    private function notifySchoolAdmins
    (
        $program,
        $programDTO,
    )
    {
        if ($programDTO->ownedby->accountType !== 'school') { 
            return;
        }

        $userIds = array_filter(
            $programDTO->ownedby->getAdminIds(),
            function($id) use ($programDTO){
                return $id !== $programDTO->userId;
            }
        );

        $notification = $this->getNotification(
            $program, $programDTO
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
        $program,
        $programDTO,
    )
    {
        if ($programDTO->methodType === 'created') {
            return new ProgramCreatedNotification(
                new UserAccountResource($programDTO->addedby),
                "created a program with the name: {$program->name}.
                Please go to dashboard to approve or otherwise."
            );
        }

        return null;
    }

    private function getEvent
    (
        $program,
        $programDTO,
    )
    {
        if ($programDTO->methodType === 'created') {
            return new NewProgramEvent([
                'account' => $programDTO->owner,
                'accountId' => $programDTO->ownerId,
                'classes' => $program->classes,
                'program' => new ProgramResource($program),
            ]);
        }

        if ($programDTO->methodType === 'updated') {
            return new UpdateProgramEvent([
                'account' => class_basename_lower($program->ownedby),
                'accountId' => $program->ownedby->id,
                'program' => new DashboardProgramResource($program),
            ]);
        }

        if ($programDTO->methodType === 'deleted') {
            return new DeleteProgramEvent([
                'account' => class_basename_lower($program->ownedby),
                'accountId' => $program->ownedby->id,
                'classes' => $program->classes,
                'programId' => $programDTO->programId,
            ]);
        }

        return null;
    }

    private function broadCastProgram
    (
        $program,
        $programDTO,
    )
    {
        $event = $this->getEvent($program, $programDTO);

        if (is_null($event)) {
            return;
        }

        broadcast($event)->toOthers();
    }

    private function throwProgramException
    (
        $message,
        $data = null
    )
    {
        throw new ProgramException(
            message: $message,
            data: $data
        );
    }

    private function addMainProgramDetails
    (
        $program,
        ProgramDTO $programDTO
    )
    {
        $this->createAttachments(
            $program,
            $programDTO->addedby,
            $programDTO->attachments,
            $programDTO->facilitate
        );

        $attachedItems = $this->attachToItems(
            attachments: $programDTO->items,
            attachable: $program,
            dto: $programDTO
        );

        $program = $this->setPayment(
            item: $program,
            addedby: $programDTO->addedby,
            paymentType: $programDTO->type,
            paymentData: $programDTO->paymentData,
        );

        $program = $this->createAutoDiscussion(
            item: $program,
            itemData: $programDTO
        );

        $this->trackSchoolAdmin($program, $programDTO);
        
        $this->updateAccountProgramFacilitation(
            program: $program, 
            programDTO: $programDTO, 
            attachedItems: $attachedItems
        );

        if ($programDTO->account === 'facilitator' || $programDTO->account === 'professional') {
            
            if ($programDTO->facilitate) {
                self::programAttachItem($program->id,$programDTO->addedby,'facilitate');
            } else {
                self::programUnattachItem($program->id,$programDTO->addedby);
            }
        }
    }

    private function updateAccountProgramFacilitation
    (
        $program, 
        $programDTO,
        $attachedItems = [],
        $detachedItems = []
    )
    {
        if ($programDTO->addedby->accountType !== 'facilitator' && 
            $programDTO->addedby->accountType !== 'professional') {
            return;
        }
        
        if ($programDTO->facilitate) {
            self::programAttachItem($program->id,$programDTO->addedby,'facilitate');
            
            $this->attachFacilitatingAccountToItems(
                $program, $programDTO, $attachedItems
            );
            
            return $program->refresh();
        }

        self::programUnattachItem($program->id,$programDTO->addedby);
        
        $this->detachFacilitatingAccountToItems(
            $program, $programDTO, $detachedItems
        );

        return $program->refresh();
    }

    private function attachFacilitatingAccountToItems
    (
        $program,
        $programDTO,
        $attachedItems,
    )
    {
        foreach ($attachedItems as $facilitatable) {

            if ($facilitatable->doesntUseFacilitationDetail()) {
                continue;
            }
            
            FacilitationService::addFacilitationDetailsWithModels(
                $program,
                $facilitatable,
                $programDTO->addedby
            );
        }
    }

    private function detachFacilitatingAccountToItems
    (
        $program,
        $programDTO,
        $detachedItems
    )
    {
        foreach ($detachedItems as $facilitatable) {

            if ($facilitatable->doesntUseFacilitationDetail()) {
                continue;
            }

            FacilitationService::removeFacilitationDetailsWithModels(
                $program,
                $facilitatable,
                $programDTO->addedby
            );
        }
    }
    
    public function getProgram($programId)
    {
        $program = $this->getModel('program',$programId);

        return $program;
    }
    
    public function getPrograms()
    {
        
    }
    
    public function updateProgram(ProgramDTO $programDTO)
    {
        $programDTO = $programDTO->withAddedby(
            $this->getModel($programDTO->account,$programDTO->accountId)
        );
        
        $this->checkAccountOwnership($programDTO->addedby,$programDTO);

        $program = $this->getModel('program',$programDTO->programId);
        
        $this->checkProgramAuthorization($program,$programDTO);

        $program->update([
            'name' => $programDTO->name,
            'state' => $programDTO->state && strlen($programDTO->state) ?
                Str::upper($programDTO->state) : "PENDING",
            'description' => $programDTO->description,
        ]);

        $this->addMainProgramDetails(
            $program,
            $programDTO,
        );
        
        $this->detachFromItems(
            attachments: $programDTO->removedItems,
            attachable: $program,
        );

        $this->removeAttachments( 
            item: $program,
            account: ($programDTO->account === 'facilitator' || $programDTO->account === 'professional') ? 
                $programDTO->addedby : null,
            facilitate: $programDTO->facilitate,
            attachments: $programDTO->removedAttachments,
        );

        $this->removePayment(
            item: $program,
            paymentData: $programDTO->removedPaymentData,
        );

        $programDTO->methodType = 'updated';
        $this->broadcastProgram($program, $programDTO);

        return $program;
    }

    public function checkProgramAuthorization($program,$programDTO)
    {
        if ($this->hasAuthorization($program,$programDTO->userId)) {
            return;
        }
        
        $this->throwProgramException(
            message: "You are not authorized to edit or delete the program with id {$program->id}",
            data: $programDTO
        );
    }

    public function deleteProgram(ProgramDTO $programDTO)
    {
        $program = $this->getModel('program',$programDTO->programId);
        
        $this->checkProgramAuthorization($program,$programDTO);

        $this->trackSchoolAdmin($program, $programDTO);

        if ($programDTO->action === 'undo') {
            return $this->changeState($program,'accepted');
        } 
        
        if ($this->paymentMadeFor($program) || $this->usedByAnotherItem($program)) {
            return $this->changeState($program,'deleted');
        }
        
        $programDTO->methodType = 'deleted';
        $this->broadcastProgram($program, $programDTO);

        $program->delete();
        return null;            
    }

    private function broadcastUpdate($program)
    {
        broadcast(new UpdateProgramEvent([
            'account' => class_basename_lower($program->ownedby),
            'accountId' => $program->ownedby->id,
            'program' => new DashboardProgramResource($program),
        ]))->toOthers();
    }

    private function paymentMadeFor($program)
    {
        if (
            $program->whereHas('payments')
                ->orWhereHas('courses',function($query) {
                    $query->whereHas('payments');
                })
                ->first()
        ) {
            return true;
        }
        return false;
    }

    private function usedByAnotherItem($program)
    {
        if (Programmable::where('program_id',$program->id)
            ->where('resource',true)->count()) {
            return true;
        }
        return false;
    }
}