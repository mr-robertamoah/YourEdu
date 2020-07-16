<?php

namespace App\YourEdu;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    //

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function deposits()
    {
        return $this->hasMany(Deposit::class);
    }

    public function withdrawals()
    {
        return $this->hasMany(Withdrawal::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
