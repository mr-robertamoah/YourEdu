<?php

namespace App\YourEdu;

use App\User;
use Database\Factories\MessageFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    use SoftDeletes,
        HasFactory;

    protected $fillable = [
        'from_user_id','to_user_id','state','message','user_deletes','updated_at'
    ];

    protected $touches = ['conversation','messageable'];

    protected $casts = [
        'user_deletes' => 'json'
    ];

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    public function messageable()
    {
        return $this->morphTo();
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

    public function flags()
    {
        return $this->morphMany(Flag::class,'flaggable');
    }

    protected static function newFactory()
    {
        return MessageFactory::new();
    }
}
