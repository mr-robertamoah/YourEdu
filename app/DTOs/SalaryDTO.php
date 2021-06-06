<?php

namespace App\DTOs;

use App\YourEdu\Salary;
use Illuminate\Database\Eloquent\Model;

class SalaryDTO
{
    public ?Model $dashboardItem = null;
    public ?Model $addedby = null;
    public ?Model $ownedby = null;
    public ?Salary $salary = null;
    public string $type = 'salary';
    public ?string $amount = null;
    public ?string $salaryId = null;
    public ?string $currency = null;
    public ?string $period = null;
    public ?string $name = null;
    public ?AccountDTO $dashboardItemDTO = null;

    public static function createFromData
    (
        $amount = null,
        $salaryId = null,
        $addedby = null,
        $dashboardItem = null,
        $currency = null,
        $period = null,
        $name = null,
    )
    {
        $self = new static;

        $self->salaryId = $salaryId;
        $self->amount = $amount;
        $self->addedby = $addedby;
        $self->name = $name;
        $self->period = $period;
        $self->currency = $currency;
        $self->dashboardItem = $dashboardItem;

        return $self;
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
    
    public function withSalary(Salary $salary)
    {
        $clone = clone $this;

        $clone->salary = $salary;

        return $clone;
    }

    public function withDashboardItem(Model $dashboardItem)
    {
        $clone = clone $this;

        $clone->dashboardItem = $dashboardItem;

        return $clone;
    }

    public function withDashboardItemDTO(AccountDTO $dashboardItemDTO)
    {
        $clone = clone $this;

        $clone->dashboardItemDTO = $dashboardItemDTO;

        return $clone;
    }

}
