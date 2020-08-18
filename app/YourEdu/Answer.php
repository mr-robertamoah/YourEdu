<?php

namespace App\YourEdu;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Answer extends Model
{
    //
    use SoftDeletes;

    protected $fillable = ['answer','work_id','possible_answer_id'];
    
    public function answerable()
    {
        return $this->morphTo();
    }
    
    public function answeredby()
    {
        return $this->morphTo();
    }

    public function work()
    {
        return $this->belongsTo(Work::class);
    }

    public function likes(){
        return $this->morphMany(Like::class,'likeable');
    }

    // public function marks()
    // {
    //     return $this->hasMany(Mark::class);
    // }

    public function marks()
    {
        return $this->morphMany(Mark::class,'markable');
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

    public function comments()
    {
        return $this->morphMany(Comment::class,'commentable');
    }

    public function possibleAnswer()
    {
        return $this->belongsTo(PossibleAnswer::class);
    }
}
