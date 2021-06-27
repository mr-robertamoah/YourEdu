<?php

namespace App\YourEdu;

use Database\Factories\AssessmentSectionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssessmentSection extends Model
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

    protected $fillable = [
        'name','instruction','position','auto_mark','answer_type',
        'assessment_id','max_questions', 'random'
    ];

    protected $casts = [
        'auto_mark' => 'bool',
        'random' => 'bool'
    ];

    public function assessment()
    {
        return $this->belongsTo(Assessment::class);
    }

    public function questions()
    {
        return $this->morphMany(Question::class,'questionable');
    }

    public function willHaveEnoughQuestions($dto)
    {
        return $this->questions->count() - count(
            array_filter($dto->removedQuestions, function($question) {
                return in_array(
                    $question->questionId,
                    $this->questions->pluck('id')->toArray()
                );
            })
        ) + count($dto->questions) >= $this->maxQuestionsCount();
    }

    public function maxQuestionsCount()
    {
        return $this->random ? $this->max_questions : 1;
    }

    public function scopeOrderedByPosition($query)
    {
        return $query->orderBy('position', 'asc');
    }

    protected static function newFactory()
    {
        return AssessmentSectionFactory::new();
    }
}
