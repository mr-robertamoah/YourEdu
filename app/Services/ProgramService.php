<?php

namespace App\Services;

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

    public function createProgram(ProgramDTO $programDTO)
    {
        $mainAccount = getYourEduModel($programDTO->account,$programDTO->accountId);
        if (is_null($mainAccount)) {
            throw new AccountNotFoundException("{$programDTO->account} not found with id {$programDTO->accountId}");
        }

        $this->checkAccountOwnership($mainAccount,$programDTO);

        ray($programDTO)->green();
        $program = $mainAccount->addedPrograms()->create([
            'name' => $programDTO->name,
            'state' => $programDTO->owner === 'school' && 
                ($programDTO->account !== 'admin' && $programDTO->account !== 'school') 
                ? 'PENDING' : 'ACCEPTED',
            'description' => $programDTO->description,
        ]);

        if (is_null($program)) {
            throw new ProgramException("program creation failed");
        }

        if ($programDTO->account === $programDTO->owner && $programDTO->accountId === $programDTO->ownerId) {
            $mainOwner = $mainAccount;
        } else {
            $mainOwner = getYourEduModel($programDTO->owner,$programDTO->ownerId);
            if (is_null($mainOwner)) {
                throw new AccountNotFoundException("{$programDTO->owner} not found with id {$programDTO->ownerId}");
            }
        }        

        $program->ownedby()->associate($mainOwner);
        $program->save();

        $this->itemCreateUpdateMethodParts(
            $program,
            $mainAccount,
            $programDTO,
            __METHOD__
        );

        if ($program->state === 'PENDING') { //it is only pending if it belongs to school and it is created by a non owner or non admin
            $userIds = array_filter($mainOwner->getAdminIds(),function($userId) use ($programDTO){
                return $userId !== $programDTO->userId;
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

        if ($programDTO->owner === 'school') {
            broadcast(new NewProgramEvent([
                'account' => $programDTO->owner,
                'accountId' => $programDTO->ownerId,
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
        ProgramDTO $programDTO,
        $method
    )
    {
        //attachments like programs programs grades
        $this->createAttachments(
            $program,
            $mainAccount,
            $programDTO->attachments,
            $programDTO->facilitate
        );

        //for classes and programs to which we may attach program
        $this->createMainAttachments(
            attachments: $programDTO->items,
            method: 'programs',
            itemId: $program->id,
            userId: $programDTO->userId
        );

        //set payment information
        $this->setPayment(
            item: $program,
            addedby: $mainAccount,
            paymentType: $programDTO->type,
            paymentData: $programDTO->paymentData,
        );

        //create auto discussion 
        $this->createAutoDiscussion(
            item: $program,
            itemData: $programDTO
        );

        //track school activities
        if ($programDTO->account === 'admin') {
            (new ActivityTrackService())->trackActivity(
                $program,$program->ownedby,$mainAccount,$method
            );
        } else if ($programDTO->account === 'facilitator' || $programDTO->account === 'professional') {
            //update program relations
            if ($programDTO->facilitate) { //facilitate
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
    
    public function updateProgram(ProgramDTO $programDTO)
    {
        //check account
        $mainAccount = getYourEduModel($programDTO->account,$programDTO->accountId);
        if (is_null($mainAccount)) {
            throw new AccountNotFoundException("$programDTO->account not found with id {$programDTO->accountId}");
        }

        $this->checkAccountOwnership($mainAccount,$programDTO);

        //check program
        $program = getYourEduModel('program',$programDTO->programId);
        if (is_null($program)) {
            throw new AccountNotFoundException("program not found with id {$programDTO->programId}");
        }

        ray($programDTO)->green();
        //check authorization
        $this->checkProgramAuthorization($program,$programDTO->userId);

        //update program attributes
        $program->update([
            'name' => $programDTO->name,
            'state' => Str::upper($programDTO->state),
            'description' => $programDTO->description,
        ]);

        $this->itemCreateUpdateMethodParts(
            $program,
            $mainAccount,
            $programDTO,
            __METHOD__
        );
        
        //for classes and programs from which we may detach program
        $this->removeMainAttachments(
            attachments: $programDTO->removedItems,
            method: 'programs',
            itemId: $program->id,
        );

        $this->removeAttachments( //remove attachments
            item: $program,
            account: ($programDTO->account === 'facilitator' || $programDTO->account === 'professional') ? $mainAccount : null,
            facilitate: $programDTO->facilitate,
            attachments: $programDTO->removedAttachments,
        );

        //set payment information
        $this->removePayment(
            item: $program,
            paymentData: $programDTO->removedPaymentData,
        );

        //broadcast
        $this->broadcastUpdate($program);

        ray()->trace();
        //return program
        return $program;
    }

    public function checkProgramAuthorization($program,$userId)
    {
        if (!$this->doesntHaveAuthorization($program,$userId)) {
            throw new ProgramException("You are not authorized to edit or delete the program with id {$program->id}");
        }
    }

    public function deleteProgram(ProgramDTO $programDTO)
    {
        $program = getYourEduModel('program',$programDTO->programId);
        if (is_null($program)) {
            throw new AccountNotFoundException("program not found with id {$programDTO->programId}");
        }

        $this->checkProgramAuthorization($program,$programDTO->userId);

        if ($programDTO->adminId) {
            $admin = getYourEduModel('admin',$programDTO->adminId);
            (new ActivityTrackService())->trackActivity(
                $program,$program->ownedby,$admin,__METHOD__
            );
        }

        if ($programDTO->action === 'undo') {
            return $this->changeState($program,'accepted');
        } else if($programDTO->action === 'delete') {
            //check if someone has subsribed or paid or used by a program
            if ($this->paymentMadeFor($program) || $this->usedByAnother($program)) {
                return $this->changeState($program,'deleted');
            } else {
                
                broadcast(new DeleteProgramEvent([
                    'account' => class_basename_lower($program->ownedby),
                    'accountId' => $program->ownedby->id,
                    'classes' => $program->classes,
                    'programId' => $programDTO->programId,
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