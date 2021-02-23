<?php

namespace App\Services;

use App\Contracts\PaymentTypeContract;
use App\Exceptions\AccountNotFoundException;
use Illuminate\Support\Str;

class PriceService extends PaymentTypeContract
{   
    public static function set($item,$priceData,$ownedby)
    {
        //the owned by is actually added by
        $price = $item->prices()->create([
            'amount' => $priceData->amount,
            'description' => $priceData->description,
            'for' => Str::upper($priceData->for)
        ]);
        $price->ownedby()->associate($ownedby);
        $price->save();
    }

    public static function unset($item,$priceId)
    {
        $price = getYourEduModel('price', $priceId);
        if (is_null($price)) {
            throw new AccountNotFoundException("price not found with id {$priceId}");
        }

        $price->priceable()->dissociate($item);
        $price->delete();
    }
}