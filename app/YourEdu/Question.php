<?php

namespace App\YourEdu;

use App\Traits\HasAnswerableTrait;
use App\Traits\HasFilesTrait;
use App\Traits\HasPositionsTrait;
use Database\Factories\QuestionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model
{
    //
    use SoftDeletes,
        HasFactory,
        HasFilesTrait,
        HasPositionsTrait,
        HasAnswerableTrait;

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
        'body', 'state', 'published_at', 'updated_at',
        'hint', 'position', 'score_over', 'correct_possible_answers',
        'answer_type'
    ];

    protected $touches = [
        'questionable'
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'correct_possible_answers' => 'array',
    ];


    public function addedby()
    {
        return $this->morphTo();
    }

    public function questionable()
    {
        return $this->morphTo();
    }

    public function possibleAnswers()
    {
        return $this->morphMany(PossibleAnswer::class, 'question');
    }

    public function files()
    {
        return $this->morphToMany(File::class, 'fileable')
            ->withPivot(['state'])->withTimestamps();
    }

    public function audios()
    {
        return $this->morphToMany(Audio::class, 'audioable')
            ->withPivot(['state'])->withTimestamps();
    }

    public function videos()
    {
        return $this->morphToMany(Video::class, 'videoable')
            ->withPivot(['state'])->withTimestamps();
    }

    public function images()
    {
        return $this->morphToMany(Image::class, 'imageable')
            ->withPivot(['state'])->withTimestamps();
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function doesntRequirePossibleAnswers()
    {
        return $this->answer_type !== self::OPTION &&
            $this->answer_type !== self::FLOW &&
            $this->answer_type !== self::ARRANGE &&
            $this->answer_type !== self::TRUE_FALSE;
    }

    public function doesntRequireFiles()
    {
        return $this->answer_type !== self::IMAGE &&
            $this->answer_type !== self::AUDIO &&
            $this->answer_type !== self::VIDEO &&
            $this->answer_type !== self::FILE;
    }

    public function isTrueOrFalseOptionAnswerType()
    {
        return $this->answer_type === self::OPTION ||
            $this->answer_type === self::TRUE_FALSE;
    }

    public function isNormalAnswerType()
    {
        return $this->answer_type === self::NUMBER ||
            $this->answer_type === self::SHORT_ANSWER ||
            $this->answer_type === self::LONG_ANSWER;
    }

    public function isNotNormalAnswerType()
    {
        return !$this->isNormalAnswerType();
    }

    public function isArrangeFlowAnswerType()
    {
        return $this->answer_type === self::FLOW ||
            $this->answer_type === self::ARRANGE;
    }

    public function getPossibleAnswerId($option = ''): int
    {
        return $this->possibleAnswers()
            ->where('option', $option)
            ->first()?->id;
    }

    public function doesntHavePossibleAnswers()
    {
        return $this->possibleAnswers->count() < 1;
    }

    public function doesntHaveCorrectPossibleAnswers()
    {
        if (is_null($this->correct_possible_answer)) {
            return true;
        }

        return false;
    }

    public function doesntHaveRequiredNumberOfPossibleAnswers()
    {
        return $this->possibleAnswers->count() < self::MIN_NUMBER_OF_OPTIONS;
    }

    public function hasAnswerFrom($account)
    {
        return $this->answers()
            ->whereAnsweredby($account)
            ->exists();
    }

    public function doesntHaveAnswerFrom($account)
    {
        return !$this->hasAnswerFrom($account);
    }

    public function answerLike($answer)
    {
        return $this->answers()
            ->where('answer', 'LIKE', "%$answer%")
            ->first();
    }

    public function hasAnswerLike($answer)
    {
        return $this->answers()
            ->where('answer', 'LIKE', "%$answer%")
            ->exists();
    }

    public function doesntHaveAnswerLike($answer)
    {
        return !$this->hasAnswerLike($answer);
    }

    public function scopeWhereAssessment($query, $assessmentId)
    {
        return $query->whereHasMorph(
            'questionable',
            'App\\YourEdu\\AssessmentSection',
            function ($query) use ($assessmentId) {
                $query->where('assessment_id', $assessmentId);
            }
        );
    }

    protected static function newFactory()
    {
        return QuestionFactory::new();
    }
}
