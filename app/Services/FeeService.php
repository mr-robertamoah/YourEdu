<?php

namespace App\Services;

use App\Contracts\PaymentTypeContract;
use App\DTOs\FeeDTO;
use App\Exceptions\AccountNotFoundException;
use App\Exceptions\FeeException;
use App\Traits\ServiceTrait;
use App\YourEdu\Fee;

class FeeService
{
    use ServiceTrait;

    const VALIDFEEACCOUNTTYPES = ['learner', 'school'];

    public function set(FeeDTO $feeDTO)
    {
        ray($feeDTO)->green();
        $feeDTO = $this->setAddedby($feeDTO);

        $this->checkAddedbyValidity($feeDTO);

        $fee = $feeDTO->addedby?->addedFees()->create([
            'amount' => $feeDTO->amount
        ]);

        $this->checkFee($fee, $feeDTO);

        $fee->ownedby()->associate($feeDTO->ownedby);

        $this->checkFeeables($feeDTO);

        $feeDTO = $feeDTO->withFee($fee);

        $fee = $this->attachFeeablesToFee($feeDTO);
        
        $fee = $this->attachClassToFee($feeDTO);

        return $fee;
    }

    public function attachClassToFee($feeDTO)
    {
        $class = null;

        if ($feeDTO->class) {
            $class = $feeDTO->class;
        }
        
        if ($feeDTO->classId) {
            $class = $this->getModel('class', $feeDTO->classId);
        }
        
        if (is_null($class) ) {
            return $feeDTO->fee;
        }

        return $this->attachFeeableToFee(
            $feeDTO->withFeeable($class)
        );
    }

    private function checkAddedbyValidity($feeDTO)
    {
        if (in_array($feeDTO->addedby->accountType, self::VALIDFEEACCOUNTTYPES)) {
            return;
        }

        $this->throwFeeException(
            message: "{$feeDTO->addedby->accountType} is not a valid account for adding a fee",
            data: $feeDTO
        );
    }

    private function setAddedby($feeDTO)
    {
        if ($feeDTO->addedby) {
            return $feeDTO;
        }

        return $feeDTO->withAddedby(
            $this->getModel($feeDTO->account, $feeDTO->accountId)
        );
    }

    public function attachFeeablesToFee(FeeDTO $feeDTO)
    {
        if (! $feeDTO->attachFeeables) {
            return $feeDTO->fee;
        }

        foreach ($feeDTO->feeables as $feeable) {

            $this->attachFeeableToFee(
                $feeDTO->withFeeable(
                    $this->getModel($feeable->type,$feeable->id)
                )
            );
        }

        $feeDTO->fee->save();

        return $feeDTO->fee;
    }

    public function attachFeeableToFee(FeeDTO $feeDTO,)
    {
        $feeable = $feeDTO->fee->feeables()->create();
        $feeable->feeable()->associate($feeDTO->feeable);
        $feeable->save();

        return $feeDTO->fee->refresh();
    }

    private function checkFee(Fee $fee, FeeDTO $feeDTO)
    {
        if (!is_null($fee)) {
            return;
        }

        $this->throwFeeException(
            message: "failed to create fee",
            data: $feeDTO
        );
    }

    private function checkFeeables(FeeDTO $feeDTO)
    {
        if (! $feeDTO->attachFeeables) {
            return;
        }

        if (count($feeDTO->feeables)) {
            return;
        }

        $this->throwFeeException(
            "academic year or academic year section is required for fees"
        );
    }

    private function throwFeeException($message, $data = null)
    {
        throw new FeeException(
            message: $message,
            data: $data
        );
    }

    public static function unset(FeeDTO $feeDTO)
    {
        $feeDTO->dashboardItem?->fees()
            ->where('id',$feeDTO->feeId)
            ->first()?->delete();
    }
}