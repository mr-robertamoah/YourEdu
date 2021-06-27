<?php

namespace App\Traits;

use App\Exceptions\AccountNotFoundException;
use App\Services\AuthService;
use App\User;

trait ServiceTrait
{
    private function checkAccountOwnership($account, $userId)
    {
        if ($account->isUser($userId)) {
            return;
        }
        
        $this->throwAccountNotFoundException(
            message: "sorry 😞, you do not own the account {$account->accountType} with id {$account->id}",
        );
    }

    private function getModel($type, $id)
    {
        $item = getYourEduModel($type, $id);
        if (is_null($item)) {
            $this->throwAccountNotFoundException("$type not found with id $id");
        }

        return $item;
    }

    private function throwAccountNotFoundException($message, $data = null)
    {
        throw new AccountNotFoundException(
            message: $message,
            data: $data
        );
    }

    private function checkUserFromDTO($dto)
    {
        if ($this->isValidUser($dto)) {
            return;
        }

        $this->throwAccountNotFoundException(
            message: "you are not a user",
            data: $dto
        );
    }

    private function isValidUser($dto)
    {
        if (is_null($dto->userId)) {
            return false;
        }
        
        return User::where('id', $dto->userId)->exists();
    }

    private function increasePointsOfAccount($account)
    {
        if (! in_array($account->accountType, AuthService::VALID_ACCOUNT_TYPES)) {
            return;
        }

        if (is_null($account->point)) {
            return;
        }

        $account->point->value = $account->point->value + 1;
        $account->point->save();
    }

    private function getUserAndParenstUserIds($dto)
    {
        $user = $this->getModel('user', $dto->userId);

        $userIds = [];
        if ($user->learner?->hasParents()) {
            $userIds = $user->learner->getParentsUserIds();
        }

        $userIds[] = $user->id;

        return $userIds;
    }
}
