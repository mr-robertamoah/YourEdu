<?php

namespace App\Services;

use Illuminate\Support\Str;

class PriceService
{   
    public function setPrice($item,$priceData,$ownedby)
    {
        //the owned by is actually added by
        $price = $item->prices()->create([
            'amount' => $priceData->amount,
            'for' => Str::upper($priceData->for)
        ]);
        $price->ownedby()->associate($ownedby);
        $price->save();
    }

    public static function __callStatic($method, $arguments)
    {
        self::$method(...$arguments);
    }
}