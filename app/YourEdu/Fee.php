<?php

namespace App\YourEdu;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Fee extends Model
{
    //
    use SoftDeletes;

    protected $fillable = [
        'class_id', 'amount'
    ];

    public function feeable()
    {
        return $this->morphTo();
    }

    public function addedby()
    {
        return $this->morphTo();
    }

    public function class()
    {
        return $this->belongsTo(ClassModel::class,'class_id');
    }
}
