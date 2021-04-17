<?php

namespace App\DTOs;

use Illuminate\Database\Eloquent\Model;

class SubscriptionDTO
{
    public ?Model $dashboardItem = null;
    public ?Model $addedby = null;
    public string $type = 'subscription';
    public ?string $amount = null;
    public ?string $subscriptionId = null;
    public ?string $description = null;
    public ?string $for = null;
    public ?string $period = null;
    public ?string $name = null;

    public static function createFromData
    (
        $amount = null,
        $subscriptionId = null,
        $addedby = null,
        $dashboardItem = null,
        $description = null,
        $for = null,
        $period = null,
        $name = null,
    )
    {
        $self = new static;

        $self->subscriptionId = $subscriptionId;
        $self->amount = $amount;
        $self->addedby = $addedby;
        $self->description = $description;
        $self->name = $name;
        $self->period = $period;
        $self->for = $for;
        $self->dashboardItem = $dashboardItem;

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
