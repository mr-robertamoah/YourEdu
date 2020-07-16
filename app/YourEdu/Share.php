<?php

namespace App\YourEdu;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Share extends Model
{
    //
    use SoftDeletes;

    public function ownedby()
    {
        return $this->morphTo();
    }

    public function shareable()
    {
        return $this->morphTo();
    }
}
