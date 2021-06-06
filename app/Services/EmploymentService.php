<?php

namespace App\Services;

use App\DTOs\EmploymentDTO;
use App\Exceptions\EmploymentException;

class EmploymentService
{
    

    public function createEmployment(EmploymentDTO $employmentDTO)
    {
        $employment = $this->makeEmployment($employmentDTO);

        $this->checkEmployment($employment);

        $employmentDTO = $employmentDTO->withEmployment($employment);

        $employment = $this->associateEmploymentToEmployee($employmentDTO);

        $this->associateEmploymentToSalary($employmentDTO);

        return $employment;
    }

    private function makeEmployment($employmentDTO)
    {
        return $employmentDTO->employer->employments()->create([
            'start_date' => now()
        ]);
    }

    private function checkEmployment($employment)
    {
        if (is_not_null($employment)) {
            return;
        }

        $this->throwEmploymentException(
            message: "failed creating employment"
        );
    }

    private function throwEmploymentException($message, $data = null)
    {
        throw new EmploymentException(
            message: $message,
            data: $data
        );
    }

    private function associateEmploymentToEmployee(EmploymentDTO $employmentDTO)
    {
        $employmentDTO->employment->employee()->associate($employmentDTO->employee);
        $employmentDTO->employment->save();

        return $employmentDTO->employment;
    }

    private function associateEmploymentToSalary(EmploymentDTO $employmentDTO)
    {
        if (is_null($employmentDTO->salariable)) {
            return;
        }

        $employmentDTO->employment->salariables()->save($employmentDTO->salariable);

        return $employmentDTO->employment;
    }
}
