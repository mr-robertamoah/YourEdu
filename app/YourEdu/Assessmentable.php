<?php

namespace App\YourEdu;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assessmentable extends Model
{
    use HasFactory;

    protected $table = "assessmentables";

    public function assessmentable()
    {
        return $this->morphTo();
    }

    public function itemable()
    {
        return $this->morphTo();
    }

    protected static function newFactory()
    {
        //
    }
}
