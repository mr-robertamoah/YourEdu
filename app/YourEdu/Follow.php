<?php

namespace App\YourEdu;

use Illuminate\Database\Eloquent\Model;

class Follow extends Model
{
    //

    protected $fillable = ['user_id', 'followed_user_id','followedby_chat_status',
        'followable_chat_status'];

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
    
    public function scopeWithAccounts($query)
    {
        $query->with(['followable.profile.images','followedby.profile.images']);
    }
}
