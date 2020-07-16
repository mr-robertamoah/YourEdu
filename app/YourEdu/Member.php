<?php

namespace App\YourEdu;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Member extends Model
{
    //
    use SoftDeletes;

    public function memberable()
    {
        return $this->morphTo();
    }

    public function paymentsMade()
    {
        return $this->morphMany(Payment::class,'paidby');
    }
}
