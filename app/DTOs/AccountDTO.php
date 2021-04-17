<?php

namespace App\DTOs;

class AccountDTO
{
    public ?string $account = null;
    public ?string $accountId = null;

    public static function createFromArray(array $dataArray) : array
    {
        $accounts = [];

        foreach ($dataArray as $data) {
            $accounts[] = static::createFromData(
                account: $data->account ?? null,
                accountId: $data->accountId ?? null,
            );
        }

        return $accounts;
    }

    public static function createFromData
    (
        $account = null,
        $accountId = null,
    )
    {
        $static = new static;

        $static->account = $account;
        $static->accountId = $accountId;

        return $static;
    }
}
