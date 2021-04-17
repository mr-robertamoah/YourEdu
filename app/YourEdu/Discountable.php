<?php

namespace App\YourEdu;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discountable extends Model
{
    use HasFactory;

    public function discountable()
    {
        return $this->morphTo();
    }

    public function beneficiary()
    {
        return $this->morphTo();
    }

    protected static function newFactory()
    {
        //
    }
}
