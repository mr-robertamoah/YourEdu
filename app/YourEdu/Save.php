<?php

namespace App\YourEdu;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Save extends Model
{
    //

    use SoftDeletes;

    protected $fillable = ['user_id'];

    public function saveable()
    {
        return $this->morphTo();
    }

    public function savedby()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
