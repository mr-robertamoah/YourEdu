<?php

namespace App\Services;

use App\Contracts\PaymentTypeContract;
use App\DTOs\PriceDTO;
use App\Exceptions\AccountNotFoundException;
use App\Exceptions\PriceException;
use App\Traits\ServiceTrait;
use Illuminate\Support\Str;

class PriceService
{   
    use ServiceTrait;

    public static function set(PriceDTO $priceDTO)
    {
        $price = $priceDTO->dashboardItem->prices()->create([
            'amount' => $priceDTO->amount,
            'description' => $priceDTO->description ?? '',
            'for' => Str::upper($priceDTO->for ?? 'ALL')
        ]);

        (new static)->checkPrice($price, $priceDTO);

        $price->ownedby()->associate($priceDTO->addedby);
        $price->save();

        return $price;
    }

    private function checkPrice($price, $priceDTO)
    {
        if (!is_null($price)) {
            return;
        }

        $this->throwPriceException(
            message: 'failed to create price',
            data: $priceDTO
        );
    }

    private function throwPriceException($message, $data = null)
    {
        throw new PriceException(
            message: $message,
            data: $data
        );
    }

    public static function unset(PriceDTO $priceDTO)
    {
        $price = (new static)->getModel('price', $priceDTO->priceId);
        
        $price->priceable()->dissociate($priceDTO->dashboardItem);
        $price->delete();
    }
}