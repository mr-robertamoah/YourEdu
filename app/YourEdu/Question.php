<?php

namespace App\YourEdu;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model
{
    //
    use SoftDeletes;
    
    const IMAGE = 'IMAGE';
    const VIDEO = 'VIDEO';
    const AUDIO = 'AUDIO';
    const OPTION = 'OPTION';
    const NUMBER = 'NUMBER';
    const ARRANGE = 'ARRANGE';
    const FLOW = 'FLOW';
    const TRUE_FALSE = 'TRUE_FALSE';
    const LONG_ANSWER = 'LONG_ANSWER';
    const SHORT_ANSWER = 'SHORT_ANSWER';

    const int MIN_NUMBER_OF_OPTIONS = 0;

    protected $fillable = [
        'question', 'state','published','user_deletes','updated_at',
        'hint','position'
    ];

    protected $touches = [
        'questionable'
    ];

    protected $casts = [
        'user_deletes' => 'json'
    ];


    public function questionedby(){
        return $this->morphTo();
    }

    public function questionable(){
        return $this->morphTo();
    }
    
    public function answers()
    {
        return $this->morphMany(Answer::class,'answerable');
    }

    public function possibleAnswers()
    {
        return $this->morphMany(PossibleAnswer::class,'question');
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

    public function doesntRequireOptionalAnswers()
    {
        return $this->answer_type !== self::OPTION &&
            $this->answer_type !== self::FLOW && 
            $this->answer_type !== self::ARRANGE &&
            $this->answer_type !== self::TRUE_FALSE;
    }

    public function doesntHaveOptionalAnswers()
    {
        return $this->possibleAnswers->count() < 1;
    }

    public function doesntHaveRequiredNumberOfOptionalAnswers()
    {
        return $this->possibleAnswers->count() < self::MIN_NUMBER_OF_OPTIONS;
    }
}
