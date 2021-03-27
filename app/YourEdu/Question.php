<?php

namespace App\YourEdu;

use Database\Factories\QuestionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model
{
    //
    use SoftDeletes, HasFactory;
    
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
    const FILE = 'FILE';

    const MIN_NUMBER_OF_OPTIONS = 0;

    protected $fillable = [
        'body', 'state','published_at','user_deletes','updated_at',
        'hint','position', 'score_over', 'correct_possible_answers',
        'answer_type'
    ];

    protected $touches = [
        'questionable'
    ];

    protected $casts = [
        'user_deletes' => 'json',
        'published_at' => 'datetime',
        'correct_possible_answers' => 'array',
    ];


    public function addedby(){
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

    public function isTrueOrFalseOptionAnswerType()
    {
        return $this->answer_type === self::OPTION ||
            $this->answer_type === self::TRUE_FALSE;
    }

    public function isArrangeFlowAnswerType()
    {
        return $this->answer_type === self::FLOW ||
            $this->answer_type !== self::ARRANGE;
    }

    public function getPossibleAnswerId($option = '') : int
    {
        return $this->possibleAnswers()
            ->where('option', $option)
            ->first()?->id;
    }

    public function doesntHaveOptionalAnswers()
    {
        return $this->possibleAnswers->count() < 1;
    }

    public function doesntHaveRequiredNumberOfOptionalAnswers()
    {
        return $this->possibleAnswers->count() < self::MIN_NUMBER_OF_OPTIONS;
    }
    
    public function allFiles()
    {
        $files = $this->images;
        $files = $files->merge($this->videos);
        $files = $files->merge($this->audios);
        $files = $files->merge($this->files);

        return $files;
    }

    public function scopeOrderedByPosition($query)
    {
        return $query->orderBy('position', 'asc');
    }
    
    protected static function newFactory()
    {
        return QuestionFactory::new();
    }
}
