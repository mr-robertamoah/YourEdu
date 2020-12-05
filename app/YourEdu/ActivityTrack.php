<?php

namespace App\YourEdu;

use Illuminate\Database\Eloquent\Model;

class ActivityTrack extends Model
{
    //
    protected $fillable = ['action'];

    public function for()
    {
        return $this->morphTo();
    }

    public function what()
    {
        return $this->morphTo();
    }

    public function who()
    {
        return $this->morphTo();
    }
}
