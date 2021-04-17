<?php

namespace App\YourEdu;

use Database\Factories\FeeFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Fee extends Model
{
    //
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'class_id', 'amount'
    ];

    public function feeables()
    {
        return $this->hasMany(Feeable::class);
    }

    public function addedby()
    {
        return $this->morphTo();
    }

    public function ownedby()
    {
        return $this->morphTo();
    }

    public function class()
    {
        return $this->belongsTo(ClassModel::class,'class_id');
    }

    protected static function newFactory()
    {
        return FeeFactory::new();
    }
}
