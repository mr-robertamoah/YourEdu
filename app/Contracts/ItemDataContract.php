<?php

namespace App\Contracts;

use App\DTOs\FeeDTO;
use App\DTOs\PaymentDTO;
use App\DTOs\PriceDTO;
use App\DTOs\SubscriptionDTO;
use App\Traits\PaymentDTOTrait;
use Illuminate\Http\Request;

abstract class ItemDataContract
{    
    use PaymentDTOTrait;
    
    public abstract static function createFromRequest(Request $request);

}