<?php

namespace App\YourEdu;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Discussion extends Model
{
    //
    use SoftDeletes;

    public function discussionable()
    {
        return $this->morphTo();
    }

    public function discussionon()
    {
        return $this->morphTo();
    }

    public function contribution()
    {
        return $this->hasMany(Contribution::class);
    }
}
