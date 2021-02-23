<?php

namespace App\YourEdu;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssessmentSection extends Model
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
}
