<?php

namespace App\Services;

use App\DTOs\SearchDTO;
use App\Traits\ServiceTrait;
use App\YourEdu\Profile;

class SearchService
{
    const PAGINATION = 10;

    use ServiceTrait;

    public function profileSearchForHomeItem(SearchDTO $searchDTO)
    {
        ray($searchDTO)->green();
        return Profile::query()
            ->whereNotIn('user_id', $searchDTO->excludedUserIds)
            ->whereDoesntHaveFlagsFrom($searchDTO->flaggedbyUserIds)
            ->whereAccountType(getAccountClass($searchDTO->searchType))
            ->whereAccountTypes($searchDTO->accountTypes)
            ->whereSearchName($searchDTO->search)
            ->paginate(self::PAGINATION);
    }

    private function setItemModel($searchDTO)
    {
        if ($searchDTO->itemModel) {
            return $searchDTO;
        }

        return $searchDTO->withItemModel(
            $this->getModel($searchDTO->item, $searchDTO->itemId)
        );
    }
}
