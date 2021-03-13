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
        $commission = $commissionDTO->ownedby->commissions()->create([
            'percent' => $commissionDTO->percentageOwned
        ]);

        $commission->for()->associate($commissionDTO->for);

        $commission->save();
        return $commission;
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
                $commissionDTO->for,
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
            $commissionDTO->for
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
        if ($commissionDTO->for->doesntHaveCommissionAllowance(
                $commissionDTO->percentageOwned
            )) {
            static::throwCommissionError($commissionDTO->for);
        }
    }

    public static function checkCommissionAllowanceForUpdate
    (
        CommissionDTO $commissionDTO,
        $currentPercent
    )
    {
        if ($commissionDTO->for->doesntHaveCommissionAllowanceForUpdate(
                $commissionDTO->percentageOwned, $currentPercent
            )) {
            static::throwCommissionError(
                $commissionDTO->for,
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
