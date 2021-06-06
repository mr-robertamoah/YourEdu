<?php

namespace App\Traits;

use App\YourEdu\Question;

trait AccountQuestionsTrait
{
    public function questionsOwned()
    {
        return $this->morphMany(Question::class,'owned');
    }

    public function questionsAdded()
    {
        return $this->morphMany(Question::class,'addedby');
    }

    public function getAddedQuestionById($questionId)
    {
        return $this->questionsAdded()
            ->where('id', $questionId)->first();
    }

    public function numberOfAddedQuestions()
    {
        return $this->questionsAdded()->count();
    }
}
