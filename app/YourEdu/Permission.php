<?php

namespace App\YourEdu;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Permission extends Model
{
    //
    use SoftDeletes;

    public function permitting()
    {
        return $this->morphTo();
    }

    public function permissible()
    {
        return $this->morphTo();
    }

    public function price()
    {
        return $this->morphOne(Price::class, 'priceable');
    }

    public function requests()
    {
        return $this->morphMany(Request::class,'requestable');
    }
}
