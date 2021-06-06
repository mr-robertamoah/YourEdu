<?php

namespace App\DTOs;

use App\YourEdu\Employment;
use App\YourEdu\Salariable;
use App\YourEdu\Salary;
use Illuminate\Database\Eloquent\Model;

class EmploymentDTO
{
    public ?Model $employee = null;
    public ?Model $employer = null;
    public ?Employment $employment = null;
    public ?Salariable $salariable = null;
    public ?SalaryDTO $salaryDTO = null;

    public static function new()
    {
        return new static;
    }

    public function withEmployer(Model $employer)
    {
        $clone =  clone $this;

        $clone->employer = $employer;

        return $clone;
    }

    public function withEmployee(Model $employee)
    {
        $clone =  clone $this;

        $clone->employee = $employee;

        return $clone;
    }

    public function withEmployment(Model $employment)
    {
        $clone =  clone $this;

        $clone->employment = $employment;

        return $clone;
    }

    public function withSalariable(Salariable | null $salariable)
    {
        $clone =  clone $this;

        $clone->salariable = $salariable;

        return $clone;
    }

    public function withSalaryDTO(SalaryDTO | null $salaryDTO)
    {
        $clone =  clone $this;

        $clone->salaryDTO = $salaryDTO;

        return $clone;
    }
}
