<?php

namespace App\Traits;

use App\YourEdu\Participant;
use App\YourEdu\Request;

trait HasParticipantsTrait
{
    public function requests(){
        return $this->morphMany(Request::class,'requestable');
    }

    public function participants()
    {
        return $this->morphMany(Participant::class,'participation');
    }

    public function pendingJoinParticipants(){
        return $this->requests()->where('state','PENDING')
            ->with('requestfrom');
    }

    public function getParticipant($participantId)
    {
        return $this->participants()->where('id',$participantId)->first();
    }

    public function getUserIds()
    {
        $ids = $this->participants->pluck('user_id'); 
        $ids[] = $this->addedby->user_id;

        return $ids;
    }

    public function getParticipantUsingAccount($account)
    {
        return $this->participants()
            ->whereParticipant($account)
            ->first();
    }

    public function getPendingParticipantUsingAccount($account)
    {
        return $this->participants()
            ->wherePending()
            ->whereParticipant($account)
            ->first();
    }

    public function isParticipant($userId)
    {
        return $this->participants()
            ->whereParticipantByUserId($userId)
            ->exists();
    }

    public function isNotParticipant($userId)
    {
        return ! $this->isParticipant($userId);
    }

    public function getParticipantAccountUsingUserId($userId)
    {
        return $this->getParticipantUsingUserId($userId)?->accountable;
    }

    public function getParticipantUsingUserId($userId)
    {
        return $this->participants()
            ->whereParticipantByUserId($userId)
            ->first();
    }

    public function isOwner($userId)
    {
        return $this->addedby->user_id == $userId;
    }

    public function isNotOwner($userId)
    {
        return ! $this->isOwner($userId);
    }

}
