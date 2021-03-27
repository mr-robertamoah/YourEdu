<?php

namespace App\Services;

use App\DTOs\WorkDTO;
use App\Exceptions\AccountNotFoundException;

class WorkService
{
    public function submitWork(WorkDTO $workDTO)
    {
        //get account
        //check account
        //get assessment
        //check assessment access
        //create work
        //attach work to assessment
        //mark if is auto mark
        //alert assessment creator
    }

    private function getModel($account, $accountId)
    {
        $mainAccount = getYourEduModel($account, $accountId);
        if (is_null($mainAccount)) {
            throw new AccountNotFoundException(
                message: "$account with id $accountId not found"
            );
        }

        return $mainAccount;
    }
}
