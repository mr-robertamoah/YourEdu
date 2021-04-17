<?php

namespace App\Services;

use App\DTOs\SalaryDTO;
use App\Exceptions\SalaryException;
use App\Traits\ServiceTrait;
use Illuminate\Support\Str;

class SalaryService
{
    use ServiceTrait;

    public static function set(SalaryDTO $salaryDTO)
    {
        $salary = $salaryDTO->addedby->addedSalaries()->create([
            'name' => $salaryDTO->name,
            'amount' => $salaryDTO->amount,
            'currency' => $salaryDTO->currency,
            'period' => Str::upper($salaryDTO->period),
        ]);

        (new static)->checkSalary($salary, $salaryDTO);
        
        $salaryDTO = $salaryDTO->withSalary($salary);

        $salary = static::associateSalaryToOwnedby($salaryDTO);

        static::associateSalaryToItem($salaryDTO);  
        
        return $salary;
    }

    public static function associateSalaryToOwnedby(SalaryDTO $salaryDTO)
    {
        if (is_null($salaryDTO->ownedby)) {
            return $salaryDTO->salary;
        }

        $salaryDTO->salary->ownedby()->associate($salaryDTO->ownedby);
        $salaryDTO->salary->save();

        return $salaryDTO->salary;
    }

    public static function associateSalaryToItem(SalaryDTO $salaryDTO)
    {
        if (is_null($salaryDTO->dashboardItem)) {
            return;
        }

        $salariable = $salaryDTO->salary->salariables()->create();
        $salariable->salariable()->associate($salaryDTO->dashboardItem);
        $salariable->save();

        return $salaryDTO->salary->refresh();
    }

    public static function associateSalaryToOwnedbyAndItem(SalaryDTO $salaryDTO)
    {
        $salaryDTO->salary = static::associateSalaryToOwnedby($salaryDTO);

        $salaryDTO->salary = static::associateSalaryToItem($salaryDTO);

        return $salaryDTO->salary;
    }

    private function checkSalary($salary, $salaryDTO)
    {
        if (!is_null($salary)) {
            return;
        }

        $this->throwSalaryException(
            message: 'failed to create salary',
            data: $salaryDTO
        );
    }

    private function throwSubscriptionException($message, $data = null)
    {
        throw new SalaryException(
            message: $message,
            data: $data
        );
    }

    public static function unset(SalaryDTO $salaryDTO)
    {
        $salary = (new static)->getModel('salary', $salaryDTO->salaryId);
        
        if (is_null($salaryDTO->dashboardItem)) {
            return;
        }

        $salariable = $salary->whereHasSpecificSalariable($salaryDTO->dashboardItem)->first();
        $salariable->salariable()->dissociate($salaryDTO->dashboardItem);
        $salariable->delete();
    }
}
