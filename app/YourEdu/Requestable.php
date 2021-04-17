<?php

namespace App\YourEdu;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Requestable extends Model
{
    use HasFactory;

    public function requests()
    {
        return $this->belongsTo(Request::class);
    }

    public function requestable()
    {
        return $this->morphTo();
    }

    protected static function newFactory()
    {
        //
    }
}
