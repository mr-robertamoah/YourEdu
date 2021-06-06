<?php

namespace App\Services;

use App\DTOs\CommissionDTO;
use App\Exceptions\CommissionException;
use App\YourEdu\Commission;
use Illuminate\Database\Eloquent\Model;

class CommissionService 
{
    public function createCommission(CommissionDTO $commissionDTO): Commission
    {
        $this->checkCommissionAllowance($commissionDTO);

        $commission = $commissionDTO->addedby->addedCommissions()->create([
            'percent' => $commissionDTO->percentageOwned
        ]);

        $this->checkCommission($commission, $commissionDTO);

        $commissionDTO = $commissionDTO->withCommission($commission);
        
        $commission = $this->associateCommissionToOwnedby($commissionDTO);

        $commission = $this->associateCommissionToItem($commissionDTO);

        return $commission;
    }

    private function checkCommission($commission, $commissionDTO)
    {
        if (is_not_null($commission)) {
            return;
        }

        $this->throwCommissionError(
            message: "creation of the commission failed",
            data: $commissionDTO
        );
    }

    private function associateCommissionToOwnedby(CommissionDTO $commissionDTO)
    {
        if (is_null($commissionDTO->ownedby)) {
            return $commissionDTO->commission;
        }

        if (is_null($commissionDTO->commission)) {
            return $commissionDTO->commission;
        }

        $commissionDTO->commission->ownedby()->associate($commissionDTO->ownedby);
        $commissionDTO->commission->save();

        return $commissionDTO->commission;
    }

    private function associateCommissionToItem(CommissionDTO $commissionDTO)
    {
        if (is_null($commissionDTO->dashboardItem)) {
            return $commissionDTO->commission;
        }

        if (is_null($commissionDTO->commission)) {
            return $commissionDTO->commission;
        }

        $commissionable = $commissionDTO->commission->commissionables()->create();
        $commissionable->Commissionable()->associate($commissionDTO->dashboardItem);
        $commissionable->save();

        return $commissionDTO->commission->refresh();
    }

    public function associateCommissionToOwnedbyAndItem
    (
        CommissionDTO $commissionDTO
    )
    {
        $commissionDTO->commission = $this->associateCommissionToOwnedby($commissionDTO);

        $commissionDTO->commission = $this->associateCommissionToItem($commissionDTO);

        return $commissionDTO->commission;
    }

    public static function updateCommissionPercentage
    (
        CommissionDTO $commissionDTO,
    ) : Commission
    {
        $commission = (new static)->getCommission($commissionDTO);
        if (is_null($commission)) {
            $account = class_basename_lower($commissionDTO->ownedby);

            static::throwCommissionError(
                $commissionDTO->dashboardItem,
                "commission percent update failed for {$account} with id {$commissionDTO->ownedby?->id}."
            );
        }
        static::checkCommissionAllowanceForUpdate(
            $commissionDTO,
            $commission->percent
        );
        $commission->percent = $commissionDTO->percentageOwned;
        $commission->save();
        
        return $commission;
    }

    private function getCommission(CommissionDTO $commissionDTO)
    {
        return $commissionDTO->ownedby?->getCommission(
            $commissionDTO->dashboardItem
        );
    }

    public function deleteCommission
    (
        CommissionDTO $commissionDTO,
    ) : bool
    {
        $commission = $this->getCommission($commissionDTO);
        
        return $commission?->delete();
    }

    public static function checkCommissionAllowance(CommissionDTO $commissionDTO)
    {
        if (is_null($commissionDTO->dashboardItem)) {
            return;
        }

        if ($commissionDTO->dashboardItem->doesntHaveCommissionAllowance(
                $commissionDTO->percentageOwned
            )) {
            static::throwCommissionError($commissionDTO->dashboardItem);
        }
    }

    public static function checkCommissionAllowanceForUpdate
    (
        CommissionDTO $commissionDTO,
        $currentPercent
    )
    {
        if (is_null($commissionDTO->dashboardItem)) {
            return;
        }
        
        if ($commissionDTO->dashboardItem->doesntHaveCommissionAllowanceForUpdate(
                $commissionDTO->percentageOwned, $currentPercent
            )) {
            static::throwCommissionError(
                $commissionDTO->dashboardItem,
                "changing the percentage share from {$currentPercent} to {$commissionDTO->percentageOwned} will make total commsions exceed 100%."
            );
        }
    }

    public static function checkCommissionPercentages(Model $item)
    {
        $sum = $item->commissions->sum('percent');

        if ($sum > 100) {
            static::throwCommissionError($item);
        }
    }

    private static function throwCommissionError
    (
        $item = null,
        $message = null
    )
    {
        $itemString = class_basename_lower($item);
        $message = is_null($message) ? 
            "the commissions for {$itemString} with id {$item->id} should not be more than 100%." :
            $message;
        throw new CommissionException(
            message: $message,
            data: $item
        );
    }
}
