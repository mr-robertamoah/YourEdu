<?php

namespace App\Traits;

/**
 * this holds methods for commission model's
 * for relationship
 */
trait CommissionTrait
{
    public function hasCommissionAllowance($percent)
    {
        return ($this->commissions->sum('percent') + $percent) <= 100;
    }
    
    public function doesntHaveCommissionAllowance($percent)
    {
        return ($this->commissions->sum('percent') + $percent) > 100;
    }
    
    public function doesntHaveCommissionAllowanceForUpdate
    (
        $updatePercent,
        $currentPercent,
    )
    {
        return ($this->commissions->sum('percent') - $currentPercent + $updatePercent) > 100;
    }
}
