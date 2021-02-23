<?php

namespace App\Services;

use App\DTOs\CommissionData;
use App\Exceptions\CommissionException;
use App\YourEdu\Commission;
use Illuminate\Database\Eloquent\Model;

class CommissionService 
{
    public function createCommission(CommissionData $commissionData): Commission
    {
        $this->checkCommissionAllowance($commissionData);
        $commission = $commissionData->ownedby->commissions()->create([
            'percent' => $commissionData->percentageOwned
        ]);

        $commission->for()->associate($commissionData->for);

        $commission->save();
        return $commission;
    }

    public static function updateCommissionPercentage
    (
        CommissionData $commissionData,
    ) : Commission
    {
        $commission = (new static)->getCommission($commissionData);
        if (is_null($commission)) {
            $account = class_basename_lower($commissionData->ownedby);

            static::throwCommissionError(
                $commissionData->for,
                "commission percent update failed for {$account} with id {$commissionData->ownedby?->id}."
            );
        }
        static::checkCommissionAllowanceForUpdate(
            $commissionData,
            $commission->percent
        );
        $commission->percent = $commissionData->percentageOwned;
        $commission->save();
        
        return $commission;
    }

    private function getCommission(CommissionData $commissionData)
    {
        return $commissionData->ownedby?->getCommission(
            $commissionData->for
        );
    }

    public function deleteCommission
    (
        CommissionData $commissionData,
    ) : bool
    {
        $commission = $this->getCommission($commissionData);
        
        return $commission?->delete();
    }

    public static function checkCommissionAllowance(CommissionData $commissionData)
    {
        if ($commissionData->for->doesntHaveCommissionAllowance(
                $commissionData->percentageOwned
            )) {
            static::throwCommissionError($commissionData->for);
        }
    }

    public static function checkCommissionAllowanceForUpdate
    (
        CommissionData $commissionData,
        $currentPercent
    )
    {
        if ($commissionData->for->doesntHaveCommissionAllowanceForUpdate(
                $commissionData->percentageOwned, $currentPercent
            )) {
            static::throwCommissionError(
                $commissionData->for,
                "changing the percentage share from {$currentPercent} to {$commissionData->percentageOwned} will make total commsions exceed 100%."
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
        $item,
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
