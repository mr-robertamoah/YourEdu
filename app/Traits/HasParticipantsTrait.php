<?php

namespace App\Traits;

use App\YourEdu\Participant;
use App\YourEdu\Request;

trait HasParticipantsTrait
{
    public function requests()
    {
        return $this->morphMany(Request::class, 'requestable');
    }

    public function participants()
    {
        return $this->morphMany(Participant::class, 'participation');
    }

    public function nonPendingParticipants()
    {
        return $this->participants()
            ->whereNotPending()
            ->get();
    }

    public function pendingParticipants()
    {
        return $this->requests()
            ->where('state', 'PENDING')
            ->with(['requestfrom', 'requestto'])
            ->get();
    }

    public function pendingParticipantAccounts()
    {
        return $this->pendingParticipants()
            ->map(function ($request) {
                if ($request->requestto->user_id === $this->addedby->user_id) {
                    return $request->requestfrom;
                }

                return $request->requestto;
            });
    }

    public function getParticipant($participantId)
    {
        return $this->participants()->where('id', $participantId)->first();
    }

    public function getUserIds()
    {
        $ids = $this->participants()
            ->whereNotPending()
            ->get()
            ->pluck('user_id');
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
        return !$this->isParticipant($userId);
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
        return !$this->isOwner($userId);
    }
}
