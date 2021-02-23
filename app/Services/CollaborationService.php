<?php

namespace App\Services;

use App\DTOs\CollaborationData;
use App\DTOs\CommissionData;
use App\Exceptions\AccountNotFoundException;
use App\Exceptions\CollaborationException;
use App\Http\Resources\DashboardCollaborationResource;
use App\Notifications\CollaborationNotification;
use App\YourEdu\Collabo;
use App\YourEdu\Collaboration;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class CollaborationService
{
    public function createCollaboration(CollaborationData $collaborationData)
    {
        $collaborationData->addedby = $this->getModel(
            $collaborationData->account,
            $collaborationData->accountId,
        );

        $collaboration = $this->addCollaboration(
            $collaborationData->addedby,$collaborationData
        );

        $collaboration = $this->addCollaborators($collaboration,$collaborationData);

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
        CollaborationData $collaborationData
    ) : Collaboration
    {
        foreach ($collaborationData->collaborators as $collaboratorDetails) {

            $collaborator = getYourEduModel($collaboratorDetails->account,$collaboratorDetails->accountId);
            if (is_null($collaborator)) {
                throw new AccountNotFoundException("{$collaboratorDetails->account} with id {$collaboratorDetails->accountId} not found");
            }

            $this->addCollaborator(
                $collaboration,
                $collaborator,
                $collaboration->addedby->is($collaborator) ? 
                Collabo::ACCEPTED : Collabo::PENDING
            );

            $this->addCommission(
                $collaboration, $collaborator, $collaboratorDetails->share
            );
        }

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
            CommissionData::createFromData(
                for: $collaboration,
                ownedby: $collaborator,
                percentageOwned: $share
            )
        );
    }

    private function addCollaborator
    (
        Collaboration $collaboration,
        $collaborator,
        $state
    ) : Collaboration
    {
        if ($collaborator->accountType !== 'facilitator' &&
            $collaborator->accountType !== 'professional') {
            $this->throwCollaborationException(
                message: "{$collaborator->accountType} cannot be a collaborator.",
                data: $collaborator
            );
        }

        $collaborator->collaborations()->attach(
            $collaboration->id,['state' => $state]
        );

        (new RequestService)->createRequest(
            from: $collaboration->addedby,
            to: $collaborator,
            data: $collaboration
        );

        return $collaboration;
    }

    private function addCollaboration
    (
        Model $addedby, 
        CollaborationData $collaborationData
    ) : Collaboration
    {
        $collaboration = $addedby->addedCollaborations()
            ->create([
                'name' => $collaborationData->name,
                'description' => $collaborationData->description,
                'type' => strtoupper($collaborationData->type),
            ]);

        if (is_null($collaboration)) {
            $this->throwCollaborationException(
                message:"creating of collaboration failed.",
                data: $collaborationData
            );
        }

        return $collaboration;
    }
    
    public function updateCollaboration(CollaborationData $collaborationData)
    {     
        $this->getModel($collaborationData->account, $collaborationData->accountId);
        
        $collaboration = $this->getCollaborationWithId($collaborationData);

        $this->checkAuthorization($collaboration, $collaborationData);

        $this->editCollaboration($collaboration,$collaborationData);

        list($collaboration, $removedCollaborators) = $this->removeCollaborators(
            $collaboration,
            $collaborationData
        );

        list($collaboration, $editedCollaborators) = $this->editCollaborators(
            $collaboration,
            $collaborationData
        );

        $collaboration = $this->addCollaborators(
            $collaboration,
            $collaborationData
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
        CollaborationData $collaborationData,
    )
    {
        $accounts = [];
        foreach ($collaborationData->editedCollaborators as $collaborator) {
            $account = getYourEduModel(
                $collaborator->account,
                $collaborator->accountId
            );
            if (is_null($account)) {
                throw new AccountNotFoundException("the {$collaborator->account} with id {$collaborator->accountId}, whose share you want to change was not found.");
            }
            
            CommissionService::updateCommissionPercentage(
                CommissionData::createFromData(
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
        CollaborationData $collaborationData,
    )
    {
        $accounts = [];
        foreach ($collaborationData->removedCollaborators as $collaborator) {
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
                CommissionData::createFromData(
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
        CollaborationData $collaborationData
    )
    {
        $collaboration->update([
            'name' => $collaborationData->name,
            'description' => $collaborationData->description,
            'type' => $collaborationData->type,
        ]);

        return $collaboration;
    }

    private function checkAuthorization($collaboration, $collaborationData)
    {
        if ($collaborationData->userId !== $collaboration->addedby->user_id &&
            $collaborationData->userId !== $collaboration->addedby->owner_id) {
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

    private function getCollaborationWithId(CollaborationData $collaborationData)
    {
        return $this->getModel('collaboration', $collaborationData->collaborationId);
    }

    private function getModel($account, $accountId)
    {
        if (is_null($account =
            getYourEduModel($account,$accountId)
        )) {
            throw new AccountNotFoundException("{$account} with id {$accountId} not found");
        }
        
        return $account;
    }

    public function deleteCollaboration(CollaborationData $collaborationData)
    {     
        $this->getModel(
            $collaborationData->account,
            $collaborationData->accountId
        );

        $collaboration = $this->getCollaborationWithId($collaborationData);

        $this->checkAuthorization($collaboration, $collaborationData);

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