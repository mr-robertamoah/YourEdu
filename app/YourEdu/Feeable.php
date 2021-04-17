<?php

namespace App\YourEdu;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feeable extends Model
{
    use HasFactory;

    public function fee()
    {
        return $this->belongsTo(Fee::class);
    }

    public function feeable()
    {
        return $this->morphTo();
    }

    protected static function newFactory()
    {
        //
    }
}
