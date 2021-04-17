<?php

namespace App\Traits;

use App\Exceptions\AccountNotFoundException;
use App\User;

trait ServiceTrait
{
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
}
