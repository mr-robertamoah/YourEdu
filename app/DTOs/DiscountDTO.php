<?php

namespace App\DTOs;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class DiscountDTO
{
    public ?string $name = null;
    public ?string $item = null;
    public ?string $itemId = null;
    public ?string $account = null;
    public bool $requiresDiscountable = true;
    public ?string $state = null;
    public ?string $accountId = null;
    public ?string $percentage = null;
    public ?string $discountedPrice = null;
    public ?Carbon $expiresAt = null;
    public ?Model $discountable = null;
    public ?Model $discount = null;
    public ?Model $beneficiary = null;
    public array $discountables = [];
    public array $beneficiaries = [];
    public ?Model $ownedby = null;
    public ?Model $addedby = null;

    public static function createFromArray($data)
    {
        
    }

    public static function createFromData
    (
        $account = null,
        $accountId = null,
        $item = null,
        $itemId = null,
        $percentage = null,
        $discountedPrice = null,
        $expiresAt = null,
        $state = null,
        $name = null,
        $ownedby = null,
        $requiresDiscountable = true,
    )
    {
        $self = new static;

        $self->account = $account;
        $self->accountId = $accountId;
        $self->requiresDiscountable = $requiresDiscountable;
        $self->item = $item;
        $self->itemId = $itemId;
        $self->percentage = static::getPercentage($percentage);
        $self->discountedPrice = $discountedPrice;
        $self->expiresAt = $expiresAt && strlen($expiresAt) ? 
            Carbon::parse($expiresAt) : now()->addDays(7);
        $self->state = $state;
        $self->ownedby = $ownedby;
        $self->name = $name;

        return $self;
    }

    private static function getPercentage($percentage)
    {
        if (is_null($percentage)) {
            return 0.1;
        }

        if (is_string($percentage)) {
            $percentage = (float) $percentage;
        }

        return number_format($percentage, 4);
    }

    public static function createFromRequest()
    {
        
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

    public function withDiscountable(Model $discountable)
    {
        $clone = clone $this;

        $clone->discountable = $discountable;

        return $clone;
    }

    public function withDiscount(Model $discount)
    {
        $clone = clone $this;

        $clone->discount = $discount;

        return $clone;
    }

    public function withBeneficiary(Model $beneficiary)
    {
        $clone = clone $this;

        $clone->beneficiary = $beneficiary;

        return $clone;
    }

    public function setToNotRequiringDiscountable()
    {
        $clone = clone $this;

        $clone->requiresDiscountable = false;

        return $clone;
    }

    public function setToRequiringDiscountable()
    {
        $clone = clone $this;

        $clone->requiresDiscountable = true;

        return $clone;
    }
}
