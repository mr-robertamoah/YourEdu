<?php

namespace App\YourEdu;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Profile extends Model
{
    //
    protected $fillable = [
        'user_id', 'name', 'about', 'interests', 'occupation', 'website', 'company', 'location', 'address',
    ];

    protected $appends = [
        'url'
    ];

    public function getUrlAttribute()
    {
        return $this->images()->where('state','PUBLIC')->where('thumbnail',1)->exists() ? 
        asset("assets/{$this->images()->where('state','PUBLIC')->where('thumbnail',1)->latest()->take(1)->first()->path}") :
        asset('storage/default.webp');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function profileable()
    {
        return $this->morphTo();
    }

    public function socials()
    {
        return $this->hasMany(SocialMedia::class);
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
        ->withPivot(['state','thumbnail'])->withTimestamps();
    }
}
