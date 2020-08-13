<?php

namespace App\YourEdu;

use Illuminate\Database\Eloquent\Model;

class Follow extends Model
{
    //

    protected $fillable = ['user_id'];

    public function followable (){
        return $this->morphTo();
    }

    public function followedby (){
        return $this->morphTo();
    }

    public function request()
    {
        return $this->morphOne(Request::class,'requestable');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
