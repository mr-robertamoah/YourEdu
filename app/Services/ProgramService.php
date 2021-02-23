<?php

namespace App\Services;

use App\DTOs\ProgramData;
use App\Events\DeleteProgramEvent;
use App\Events\NewProgramEvent;
use App\Events\UpdateProgramEvent;
use App\Exceptions\AccountNotFoundException;
use App\Exceptions\ProgramException;
use App\Http\Resources\DashboardProgramResource;
use App\Http\Resources\ProgramResource;
use App\Http\Resources\UserAccountResource;
use App\Notifications\ProgramCreatedNotification;
use App\Traits\ClassCourseTrait;
use App\User;
use App\YourEdu\Programmable;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class ProgramService
{
    use ClassCourseTrait;
    
    public function programCreate($account,$accountId,$name,$description,$rationale,$aliases)
    {
        if ($account === 'learner' || $account === 'parent') {
            throw new ProgramException('learner or parent can only create an alias of a subject');
        }

        $program = (new AttachmentService())->createAttachment($account,
            $accountId,'program',$name,$description,
            $rationale,$aliases);

        if (is_null($program)) {
            throw new ProgramException('program was not created');
        }

        return $program;
    }

    public function programAliasCreate($programId,$account,$accountId,$name,$description)
    {
        $mainProgram = getYourEduModel('program',$programId);
        if (is_null($mainProgram)) {
            throw new AccountNotFoundException("program not found with id {$programId}");
        }
        $mainAccount = getYourEduModel($account,$accountId);
        if (is_null($mainAccount)) {
            throw new AccountNotFoundException("{$account} not found with id {$accountId}");
        }

        $alias = (new AttachmentService())->createAttachmentAlias($mainAccount,$mainProgram,
            $name,$description);

        if (is_null($alias)) {
            throw new ProgramException('alias was not created');
        }

        return $mainProgram;
    }

    public function programDelete($programId,$id)
    {
        $program = getYourEduModel('program',$programId);
        if (is_null($program)) {
            throw new AccountNotFoundException("program not found with id {$programId}");
        }

        if ($program->addedby->user_id !== $id) {
            throw new ProgramException('you cannot delete program you did not create');
        }

        $program->delete();

        return 'successful';
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

    public function createProgram(ProgramData $programData)
    {
        $mainAccount = getYourEduModel($programData->account,$programData->accountId);
        if (is_null($mainAccount)) {
            throw new AccountNotFoundException("{$programData->account} not found with id {$programData->accountId}");
        }

        if (!$this->checkAccountOwnership($mainAccount,$programData->userId)) {
            throw new ProgramException("you do not own this account");
        }

        ray($programData)->green();
        $program = $mainAccount->addedPrograms()->create([
            'name' => $programData->name,
            'state' => $programData->owner === 'school' && 
                ($programData->account !== 'admin' && $programData->account !== 'school') 
                ? 'PENDING' : 'ACCEPTED',
            'description' => $programData->description,
        ]);

        if (is_null($program)) {
            throw new ProgramException("program creation failed");
        }

        if ($programData->account === $programData->owner && $programData->accountId === $programData->ownerId) {
            $mainOwner = $mainAccount;
        } else {
            $mainOwner = getYourEduModel($programData->owner,$programData->ownerId);
            if (is_null($mainOwner)) {
                throw new AccountNotFoundException("{$programData->owner} not found with id {$programData->ownerId}");
            }
        }        

        $program->ownedby()->associate($mainOwner);
        $program->save();

        $this->itemCreateUpdateMethodParts(
            $program,
            $mainAccount,
            $programData,
            __METHOD__
        );

        if ($program->state === 'PENDING') { //it is only pending if it belongs to school and it is created by a non owner or non admin
            $userIds = array_filter($mainOwner->getAdminIds(),function($userId) use ($programData){
                return $userId !== $programData->userId;
            });
            $name = $mainOwner->name ?? $mainAccount->company_name;
            Notification::send(User::whereIn('id',$userIds)->get(), 
                new ProgramCreatedNotification(
                    new UserAccountResource($mainAccount),
                    "created a program with the name: {$program->name}, 
                    for {$name}. Please go to dashboard to approve or otherwise."
                )
            );
        }

        if ($programData->owner === 'school') {
            broadcast(new NewProgramEvent([
                'account' => $programData->owner,
                'accountId' => $programData->ownerId,
                'classes' => $program->classes,
                'program' => new ProgramResource($program),
            ]))->toOthers();
        }

        return $program;
    }

    private function itemCreateUpdateMethodParts
    (
        $program,
        $mainAccount,
        ProgramData $programData,
        $method
    )
    {
        //attachments like programs programs grades
        $this->createAttachments(
            $program,
            $mainAccount,
            $programData->attachments,
            $programData->facilitate
        );

        //for classes and programs to which we may attach program
        $this->createMainAttachments(
            attachments: $programData->items,
            method: 'programs',
            itemId: $program->id,
            userId: $programData->userId
        );

        //set payment information
        $this->setPayment(
            item: $program,
            addedby: $mainAccount,
            paymentType: $programData->type,
            paymentData: $programData->paymentData,
        );

        //create auto discussion 
        $this->createAutoDiscussion(
            item: $program,
            itemData: $programData
        );

        //track school activities
        if ($programData->account === 'admin') {
            (new ActivityTrackService())->createActivityTrack(
                $program,$program->ownedby,$mainAccount,$method
            );
        } else if ($programData->account === 'facilitator' || $programData->account === 'professional') {
            //update program relations
            if ($programData->facilitate) { //facilitate
                self::programAttachItem($program->id,$mainAccount,'facilitate');
            } else {
                self::programUnattachItem($program->id,$mainAccount);
            }
        }
    }
    
    public function getProgram($programId)
    {
        $program = getYourEduModel('program',$programId);
        if (is_null($program)) {
            throw new AccountNotFoundException("program not found with id {$programId}");
        }

        return $program;
    }
    
    public function getPrograms()
    {
        
    }
    
    public function updateProgram(ProgramData $programData)
    {
        //check account
        $mainAccount = getYourEduModel($programData->account,$programData->accountId);
        if (is_null($mainAccount)) {
            throw new AccountNotFoundException("$programData->account not found with id {$programData->accountId}");
        }

        if (!$this->checkAccountOwnership($mainAccount,$programData->userId)) {
            throw new ProgramException("you do not own this account");
        }
        //check program
        $program = getYourEduModel('program',$programData->programId);
        if (is_null($program)) {
            throw new AccountNotFoundException("program not found with id {$programData->programId}");
        }

        ray($programData)->green();
        //check authorization
        $this->checkProgramAuthorization($program,$programData->userId);

        //update program attributes
        $program->update([
            'name' => $programData->name,
            'state' => Str::upper($programData->state),
            'description' => $programData->description,
        ]);

        $this->itemCreateUpdateMethodParts(
            $program,
            $mainAccount,
            $programData,
            __METHOD__
        );
        
        //for classes and programs from which we may detach program
        $this->removeMainAttachments(
            attachments: $programData->removedItems,
            method: 'programs',
            itemId: $program->id,
        );

        $this->removeAttachments( //remove attachments
            item: $program,
            account: ($programData->account === 'facilitator' || $programData->account === 'professional') ? $mainAccount : null,
            facilitate: $programData->facilitate,
            attachments: $programData->removedAttachments,
        );

        //set payment information
        $this->removePayment(
            item: $program,
            paymentData: $programData->removedPaymentData,
        );

        //broadcast
        $this->broadcastUpdate($program);

        ray()->trace();
        //return program
        return $program;
    }

    public function checkProgramAuthorization($program,$userId)
    {
        if (!$this->checkAuthorization($program,$userId)) {
            throw new ProgramException("You are not authorized to edit or delete the program with id {$program->id}");
        }
    }

    public function deleteProgram(ProgramData $programData)
    {
        $program = getYourEduModel('program',$programData->programId);
        if (is_null($program)) {
            throw new AccountNotFoundException("program not found with id {$programData->programId}");
        }

        $this->checkProgramAuthorization($program,$programData->userId);

        if ($programData->adminId) {
            $admin = getYourEduModel('admin',$programData->adminId);
            (new ActivityTrackService())->createActivityTrack(
                $program,$program->ownedby,$admin,__METHOD__
            );
        }

        if ($programData->action === 'undo') {
            return $this->changeState($program,'accepted');
        } else if($programData->action === 'delete') {
            //check if someone has subsribed or paid or used by a program
            if ($this->paymentMadeFor($program) || $this->usedByAnother($program)) {
                return $this->changeState($program,'deleted');
            } else {
                
                broadcast(new DeleteProgramEvent([
                    'account' => class_basename_lower($program->ownedby),
                    'accountId' => $program->ownedby->id,
                    'classes' => $program->classes,
                    'programId' => $programData->programId,
                ]))->toOthers();

                $program->delete();
                return null;
            }
        }
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
        // return true;
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

    private function usedByAnother($program)
    {
        if (Programmable::where('program_id',$program->id)
            ->where('resource',true)->count()) {
            return true;
        }
        return false;
    }
}