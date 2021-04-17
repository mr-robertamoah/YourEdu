<?php

namespace App\DTOs;

use App\YourEdu\ClassModel;
use Illuminate\Database\Eloquent\Model;

class FeeDTO
{
    public ?ClassModel $class = null;
    public ?Model $fee = null;
    public ?Model $addedby = null;
    public ?Model $feeable = null;
    public ?Model $ownedby = null;
    public ?Model $dashboardItem = null;
    public string $type = 'fee';
    public bool $attachFeeables = true;
    public ?string $amount = null;
    public ?string $feeId = null;
    public ?string $account = null;
    public ?string $accountId = null;
    public ?string $classId = null;
    public array $feeables = [];

    public static function createFromData
    (
        $amount = null,
        $feeId = null,
        $account = null,
        $accountId = null,
        $classId = null,
        $addedby = null,
        $dashboardItem = null,
        bool $attachFeeables = true,
        $feeables = [],
    )
    {
        $self = new static;

        $self->classId = $classId;
        $self->accountId = $accountId;
        $self->account = $account;
        $self->feeId = $feeId;
        $self->amount = $amount;
        $self->addedby = $addedby;
        $self->attachFeeables = $attachFeeables;
        $self->dashboardItem = $dashboardItem;
        $self->feeables = $feeables;

        return $self;
    }

    public function withAddedby(Model $addedby)
    {
        $clone = clone $this;

        $clone->addedby = $addedby;

        return $clone;
    }

    public function withOwnedby(Model $ownedby)
    {
        $clone = clone $this;

        $clone->ownedby = $ownedby;

        return $clone;
    }

    public function withFeeable(Model $feeable)
    {
        $clone = clone $this;

        $clone->feeable = $feeable;

        return $clone;
    }

    public function withDashboardItem(Model $dashboardItem)
    {
        $clone = clone $this;

        $clone->dashboardItem = $dashboardItem;

        return $clone;
    }

    public function withFeeables($feeables)
    {
        $clone = clone $this;

        $clone->feeables = $feeables;

        return $clone;
    }

    public function withFee($fee)
    {
        $clone = clone $this;

        $clone->fee = $fee;

        return $clone;
    }

    public function withClass($class)
    {
        $clone = clone $this;

        $clone->class = $class;

        return $clone;
    }

    public function setToNotAttachFeeables()
    {
        $clone = clone $this;

        $clone->attachFeeables = false;

        return $clone;
    }
}
