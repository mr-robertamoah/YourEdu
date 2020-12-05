<?php

namespace App\YourEdu;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Request extends Model
{
    //
    use SoftDeletes;

    protected $fillable = ['state','data'];

    public function price()
    {
        return $this->morphOne(Price::class,'priceable');
    }

    public function requestable()
    {
        return $this->morphTo();
    }

    public function messages()
    {
        return $this->morphMany(Message::class,'messageable');
    }

    public function requestfrom()
    {
        return $this->morphTo();
    }

    public function requestto()
    {
        return $this->morphTo();
    }

    public function comments()
    {
        return $this->morphMany(Comment::class,'commentable');
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
