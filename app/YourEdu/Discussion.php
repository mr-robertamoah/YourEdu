<?php

namespace App\YourEdu;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Discussion extends Model
{
    //
    use SoftDeletes;

    protected $fillable = [
        'title', 'preamble', 'restricted', 'type','allowed',
    ];

    protected $casts = [
        'restricted' => 'boolean'
    ];

    public function discussionfor()
    {
        return $this->morphTo();
    }

    public function raisedby()
    {
        return $this->morphTo();
    }

    public function messages()
    {
        return $this->morphMany(Message::class,'messageable');
    }

    public function participants()
    {
        return $this->morphMany(Participant::class,'participation');
    }

    public function likes(){
        return $this->morphMany(Like::class,'likeable');
    }

    public function requests(){
        return $this->morphMany(Request::class,'requestable');
    }

    public function pendingJoinParticipants(){
        return $this->requests()->where('state','PENDING')
            ->with('requestfrom');
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

    public function comments()
    {
        return $this->morphMany(Comment::class,'commentable');
    }

    public function flags()
    {
        return $this->morphMany(Flag::class,'flaggable');
    }

    public function beenSaved()
    {
        return $this->morphMany(Save::class,'saveable');
    }

    public function attachments()
    {
        return $this->morphMany(PostAttachment::class,'attachable');
    }

    public function scopeNotSocial($query)
    {
        return $query->whereNull('discussionfor_type');
    }
}
