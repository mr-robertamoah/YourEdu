<?php

namespace App\YourEdu;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    //
    use SoftDeletes;

    protected $fillable = ['from_user_id','to_user_id','state','message'];

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    public function fromUser()
    {
        return $this->belongsTo(User::class,'from_user_id');
    }

    public function toUser()
    {
        return $this->belongsTo(User::class,'to_user_id');
    }

    public function fromable()
    {
        return $this->morphTo();
    }

    public function toable()
    {
        return $this->morphTo();
    }

    public function files()
    {
        return $this->morphToMany(File::class,'fileable')
        ->withPivot(['state'])->withTimestamps();
    }

    public function audios()
    {
        return $this->morphToMany(Audio::class,'audioable')
        ->withPivot(['state'])->withTimestamps();
    }

    public function videos()
    {
        return $this->morphToMany(Video::class,'videoable')
        ->withPivot(['state'])->withTimestamps();
    }

    public function images()
    {
        return $this->morphToMany(Image::class,'imageable')
        ->withPivot(['state'])->withTimestamps();
    }
}
