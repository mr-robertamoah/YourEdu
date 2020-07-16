<?php

namespace App\YourEdu;

use Illuminate\Database\Eloquent\Model;

class Deposit extends Model
{
    //

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function transactions()
    {
        return $this->morphOne(Transaction::class,'transactable');
    }
}
