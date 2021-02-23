<?php

namespace App\Services;

use App\YourEdu\Profile;

class ProfileService
{
    public static function profileAccountsSearch
    (
        string | null $search,
        string $searchType, //all is inclusive
        string|null $searcherAccount,
        int|null $searcherAccountId,
        string | null $for,
    )
    {
        $accountClasses = [];
        if ($for === 'collaboration') {
            $accountClasses = [
                'App\\YourEdu\\Professional',
                'App\\YourEdu\\Facilitator',
            ];
        }

        return Profile::search(
            $search ? $search : '',
            $searchType === 'all'
                ? '' : getAccountClass($searchType),
            is_null($searcherAccount) ? '' : capitalize($searcherAccount),
            is_null($searcherAccountId) ? '' : $searcherAccountId,
            $accountClasses,
        )->paginate(2);
    }
}
