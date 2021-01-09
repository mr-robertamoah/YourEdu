<?php

namespace App\Services;

use App\Exceptions\AccountNotFoundException;
use App\Exceptions\FeeException;

class FeeService
{
    public static function setFee($item,$amount,$addedby,$feeableData)
    {
        $fee = $item->fees()->create([
            'amount' => $amount
        ]);
        if (!count($feeableData)) {
            throw new FeeException("academic year or academic year section is required for fees");
        }
        $feeable = getAccountObject($feeableData['feeable'],$feeableData['feeableId']);
        if (is_null($feeable)) {
            throw new AccountNotFoundException("{$feeableData['feeable']} was not found with id {$feeableData['feeableId']}");
        }
        $fee->feeable()->associate($feeable);
        $fee->addedby()->associate($addedby);
        $fee->save();
    }

    public static function __callStatic($method, $arguments)
    {
        self::$method(...$arguments);
    }
}