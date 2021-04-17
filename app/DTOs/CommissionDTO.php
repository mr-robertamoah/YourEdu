<?php

namespace App\DTOs;

use App\YourEdu\Commission;
use Illuminate\Database\Eloquent\Model;

class CommissionDTO
{
    public ?float $percentageOwned = null;
    public ?string $commissionId = null;
    public ?Model $dashboardItem = null;
    public ?Model $ownedby = null;
    public ?Model $addedby = null;
    public ?Commission $commission = null;

    public function __invoke($dashboardItem, $ownedby, $percentageOwned)
    {
        $this->dashboardItem = $dashboardItem;
        $this->ownedby = $ownedby;
        $this->percentageOwned = number_format((float) $percentageOwned, 4);
    }

    public static function createFromData
    (
        $commissionId = null, 
        $ownedby = null, 
        $percentageOwned = 0
    )
    {
        $self = new static;

        $self->commissionId = $commissionId;
        $self->ownedby = $ownedby;
        $self->percentageOwned = number_format((float) $percentageOwned, 4);

        return $self;
    }

    public function withCommission(Commission $commission)
    {
        $clone = clone $this;

        $clone->commission = $commission;

        return $clone;
    }

    public function withOwnedby(Model $ownedby)
    {
        $clone = clone $this;

        $clone->ownedby = $ownedby;

        return $clone;
    }

    public function withAddedby(Model $addedby)
    {
        $clone = clone $this;

        $clone->addedby = $addedby;

        return $clone;
    }

    public function withDashboardItem(Model $dashboardItem)
    {
        $clone = clone $this;

        $clone->dashboardItem = $dashboardItem;

        return $clone;
    }
}
