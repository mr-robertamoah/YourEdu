<?php

namespace App\DTOs;

use Illuminate\Database\Eloquent\Model;

class PriceDTO
{
    public ?Model $dashboardItem = null;
    public ?Model $addedby = null;
    public string $type = 'price';
    public ?string $amount = null;
    public ?string $priceId = null;
    public ?string $description = null;
    public ?string $for = null;
    
    public static function createFromData
    (
        $amount = null,
        $priceId = null,
        $addedby = null,
        $dashboardItem = null,
        $description = null,
        $for = null,
    )
    {
        $self = new static;

        $self->priceId = $priceId;
        $self->amount = $amount;
        $self->addedby = $addedby;
        $self->dashboardItem = $dashboardItem;
        $self->for = $for;
        $self->description = $description;

        return $self;
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
