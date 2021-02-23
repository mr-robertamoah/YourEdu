<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Model;

abstract class PaymentTypeContract
{
    abstract public static function set(Model $item, $data, $owner);

    abstract public static function unset(Model $item, $typeId);
}
