<?php

namespace App\YourEdu;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    //
    protected $fillable = [
        'name', 'amount', 'description', 'for', 'period'
    ];

    public function ownedby()
    {
        return $this->morphTo();
    }

    public function subscribable()
    {
        return $this->morphTo();
    }
}
