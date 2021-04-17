<?php

namespace App\YourEdu;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commissionable extends Model
{
    use HasFactory;

    public function commission()
    {
        return $this->belongsTo(Commission::class);
    }

    public function commissionable()
    {
        return $this->morphTo();
    }

    protected static function newFactory()
    {
        //
    }
}
