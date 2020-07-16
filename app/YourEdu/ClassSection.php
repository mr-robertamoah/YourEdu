<?php

namespace App\YourEdu;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClassSection extends Model
{
    //
    use SoftDeletes;

    public function class()
    {
        return $this->belongsTo(ClassModel::class,'class_id');
    }

    public function price()
    {
        return $this->morphOne(Price::class,'priceable');
    }
}
