<?php

namespace App\YourEdu;

use Illuminate\Database\Eloquent\Model;

class Commission extends Model
{
    //

    public function for()
    {
        return $this->morphTo();
    }

    public function ownedby()
    {
        return $this->morphTo();
    }
}
