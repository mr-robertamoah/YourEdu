<?php

namespace App\DTOs;

use Illuminate\Database\Eloquent\Model;

class PaymentDTO
{
    public bool $dashboardItemCheck = true;
    public ?string $type = null;
    public ?object $typeDTO = null;
    public ?Model $dashboardItem = null;
    public ?Model $addedby = null;
    public ?Model $ownedby = null;
    public array $multipleTypeDTO = [];
    public array $payments = [];

    public static function createFromData
    (
        $type = null,
        $typeDTO = null,
        $multipleTypeDTO = [],
    )
    {
        $self = new static;

        $self->type = $type;
        $self->typeDTO = $typeDTO;
        $self->multipleTypeDTO = $multipleTypeDTO;

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

    public function withDashboardItem(Model $dashboardItem)
    {
        $clone = clone $this;

        $clone->dashboardItem = $dashboardItem;

        return $clone;
    }

    public function doesntHaveRequiredData()
    {
        if (is_null($this->addedby)) {
            return true;
        }

        if (is_null($this->dashboardItem) && $this->dashboardItemCheck) {
            return true;
        }

        return false;
    }

    public function dontCheckDasboardItem()
    {
        $clone = clone $this;

        $clone->dashboardItemCheck = false;

        return $clone;
    }
}
