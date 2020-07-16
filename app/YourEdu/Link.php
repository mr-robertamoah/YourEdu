<?php

namespace App\YourEdu;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Link extends Model
{
    //
    use SoftDeletes;
    
    public function addedby()
    {
        return $this->morphTo();
    }

    public function linkable()
    {
        return $this->morphTo();
    }
}
