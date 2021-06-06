<?php

namespace App\Services;

use App\DTOs\CollaborationDTO;
use App\DTOs\CommissionDTO;
use App\Exceptions\AccountNotFoundException;
use App\Exceptions\CollaborationException;
use App\Http\Resources\DashboardCollaborationResource;
use App\Notifications\CollaborationNotification;
use App\Traits\ServiceTrait;
use App\YourEdu\Collabo;
use App\YourEdu\Collaboration;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class CollaborationService
{
    use ServiceTrait;

    public function createCollaboration(CollaborationDTO $collaborationDTO)
    {
        $collaborationDTO->addedby = $this->getModel(
            $collaborationDTO->account,
            $collaborationDTO->accountId,
        );

        $collaboration = $this->addCollaboration(
            $collaborationDTO->addedby,$collaborationDTO
        );

        $collaboration = $this->addCollaborators($collaboration,$collaborationDTO);

        $this->notifyPendingCollaborators($collaboration);

        return $collaboration;
    }

    private function notifyPendingCollaborators
    (
        Collaboration $collaboration
    )
    {
        $accounts = Collabo::where('collaboration_id', $collaboration->id)
            ->where('state','PENDING')
            ->where('collaborationable_type','!=',$collaboration->addedby::class)
            ->where('collaborationable_id','!=',$collaboration->addedby->id)
            ->with('collaborationable')
            ->get()->pluck('collaborationable');

        $accounts->each(function ($account) use ($collaboration) {
            if ($account->doesntHavePendingRequestFor($collaboration)) {
                
                $request = (new RequestService)->createRequest(
                    from: $collaboration->addedby,
                    to: $account,
                    requestable: $collaboration,
                );
    
                $account->notifyUser(new CollaborationNotification(
                    message: 
                        "{$collaboration->addedby->name} has sent you a request to join a collaboration.",
                    collaborationResource: 
                        new DashboardCollaborationResource($collaboration),
                    requestId: $request->id
                ));
            }
        });
    }

    private function notifySpecificCollaborators
    (
        Collaboration $collaboration,
        $collaborators,
        string $message
    )
    {
        foreach ($collaborators as $collaborator) {
            
            $collaborator->notifyUser(new CollaborationNotification(
                message: "$message",
                collaborationResource: new DashboardCollaborationResource($collaboration),
            ));
        }
    }

    private function addCollaborators
    (
        Collaboration $collaboration,
        CollaborationDTO $collaborationDTO
    ) : Collaboration
    {
        foreach ($collaborationDTO->collaborators as $collaboratorDetails) {

            $collaborator = $this->getModel($collaboratorDetails->account,$collaboratorDetails->accountId);

            $this->addCollaborator(
                $collaboration,
                $collaborator,
                $collaboration->addedby->is($collaborator) ? 
                Collabo::ACCEPTED : Collabo::PENDING
            );

            $this->addCommission(
                $collaboration, $collaborator, $collaboratorDetails->share
            );

            $this->createCollaborationRequest($collaboration, $collaborator);
        }

        return $collaboration->refresh();
    }

    private function createCollaborationRequest($collaboration, $collaborator)
    {
        (new RequestService)->createRequest(
            from: $collaboration->addedby,
            to: $collaborator,
            data: $collaboration
        );

        return $collaboration->refresh();
    }

    private function addCommission
    (
        $collaboration,
        $collaborator,
        $share
    )
    {
        (new CommissionService)->createCommission(
            CommissionDTO::createFromData(
                commission: $collaboration,
                ownedby: $collaborator,
                percentageOwned: $share
            )
        );
    }

    public function addCollaborator
    (
        Collaboration $collaboration,
        $collaborator,
        $state
    ) : Collaboration
    {
        $this->checkCollaboratorAccountType($collaborator);

        $collaborator->collaborations()->attach(
            $collaboration->id,['state' => $state]
        );

        return $collaboration;
    }

    private function checkCollaboratorAccountType($collaborator)
    {
        if ($collaborator->accountType === 'facilitator' ||
            $collaborator->accountType === 'professional') {
            return;
        }
        
            $this->throwCollaborationException(
            message: "{$collaborator->accountType} cannot be a collaborator.",
            data: $collaborator
        );
    }

    private function addCollaboration
    (
        Model $addedby, 
        CollaborationDTO $collaborationDTO
    ) : Collaboration
    {
        $collaboration = $addedby->addedCollaborations()
            ->create([
                'name' => $collaborationDTO->name,
                'description' => $collaborationDTO->description,
                'type' => strtoupper($collaborationDTO->type),
            ]);

        if (is_null($collaboration)) {
            $this->throwCollaborationException(
                message:"creating of collaboration failed.",
                data: $collaborationDTO
            );
        }

        return $collaboration;
    }
    
    public function updateCollaboration(CollaborationDTO $collaborationDTO)
    {     
        $this->getModel($collaborationDTO->account, $collaborationDTO->accountId);
        
        $collaboration = $this->getCollaborationWithId($collaborationDTO);

        $this->checkAuthorization($collaboration, $collaborationDTO);

        $this->editCollaboration($collaboration,$collaborationDTO);

        list($collaboration, $removedCollaborators) = $this->removeCollaborators(
            $collaboration,
            $collaborationDTO
        );

        list($collaboration, $editedCollaborators) = $this->editCollaborators(
            $collaboration,
            $collaborationDTO
        );

        $collaboration = $this->addCollaborators(
            $collaboration,
            $collaborationDTO
        );

        ray($editedCollaborators)->green();
        $this->notifySpecificCollaborators(
            $collaboration, 
            $editedCollaborators,
            "your percentage share in the collaboration with name: {$collaboration->addedby->name}, has been changed."
        );
        
        $this->notifySpecificCollaborators(
            $collaboration, 
            $removedCollaborators,
            "you have been removed from the collaboration with name: {$collaboration->addedby->name}."
        );
        
        $this->notifyPendingCollaborators($collaboration);

        return $collaboration;
    }

    private function editCollaborators
    (
        Collaboration $collaboration,
        CollaborationDTO $collaborationDTO,
    )
    {
        $accounts = [];
        foreach ($collaborationDTO->editedCollaborators as $collaborator) {
            $account = getYourEduModel(
                $collaborator->account,
                $collaborator->accountId
            );
            if (is_null($account)) {
                throw new AccountNotFoundException("the {$collaborator->account} with id {$collaborator->accountId}, whose share you want to change was not found.");
            }
            
            CommissionService::updateCommissionPercentage(
                CommissionDTO::createFromData(
                    for: $collaboration,
                    ownedby: $account,
                    percentageOwned: $collaborator->share
                )
            );

            $accounts[] = $account;
        }

        return [$collaboration->refresh(), $accounts];
    }

    private function removeCollaborators
    (
        Collaboration $collaboration,
        CollaborationDTO $collaborationDTO,
    )
    {
        $accounts = [];
        foreach ($collaborationDTO->removedCollaborators as $collaborator) {
            $account = getYourEduModel(
                $collaborator->account,
                $collaborator->accountId
            );
            if (is_null($account)) {
                throw new AccountNotFoundException("collaborator {$collaborator->account} account with id {$collaborator->accountId} not found");
            }

            Collabo::where('collaboration_id', $collaboration->id)
                ->where('collaborationable_type',$account::class)
                ->where('collaborationable_id',$account->id)
                ->first()?->delete();
            
            (new CommissionService)->deleteCommission(
                CommissionDTO::createFromData(
                    for: $collaboration,
                    ownedby: $account,
                )
            );

            $accounts[] = $account;
        }

        return [$collaboration->refresh(), $accounts];
    }

    private function editCollaboration
    (
        Collaboration $collaboration,
        CollaborationDTO $collaborationDTO
    )
    {
        $collaboration->update([
            'name' => $collaborationDTO->name,
            'description' => $collaborationDTO->description,
            'type' => $collaborationDTO->type,
        ]);

        return $collaboration;
    }

    private function checkAuthorization($collaboration, $collaborationDTO)
    {
        if ($collaborationDTO->userId !== $collaboration->addedby->user_id &&
            $collaborationDTO->userId !== $collaboration->addedby->owner_id) {
            $this->throwCollaborationException(
                message: "you are not authorized to update this collaboration with id {$collaboration->id}"
            );
        }
        return true;
    }

    private function throwCollaborationException
    (
        $message,
        $data = null
    )
    {
        throw new CollaborationException(
            message: $message, data: $data
        );
    }

    private function getCollaborationWithId(CollaborationDTO $collaborationDTO)
    {
        return $this->getModel('collaboration', $collaborationDTO->collaborationId);
    }

    public function deleteCollaboration(CollaborationDTO $collaborationDTO)
    {     
        $this->getModel(
            $collaborationDTO->account,
            $collaborationDTO->accountId
        );

        $collaboration = $this->getCollaborationWithId($collaborationDTO);

        $this->checkAuthorization($collaboration, $collaborationDTO);

        $this->removeCollaboration($collaboration);

        $this->notifyCollaborators(
            $collaboration,
            "collaboration with name: {$collaboration->name}, has been deleted."
        );
    }

    private function notifyCollaborators($collaboration, $message)
    {
        foreach ($collaboration->collaborators() as $collaborator) {
            if ($collaborator->isNot($collaboration->addedby)) {
                $collaborator->notifyUser(
                    new CollaborationNotification(
                        message: $message,
                        collaborationResource: new DashboardCollaborationResource($collaboration)
                    )
                );
            }
        }
    }

    private function removeCollaboration($collaboration)
    {
        return $collaboration->delete();
    }
}