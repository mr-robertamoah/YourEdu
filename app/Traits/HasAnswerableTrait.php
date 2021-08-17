<?php

namespace App\Traits;

use App\YourEdu\Answer;

trait HasAnswerableTrait
{
    public function answers()
    {
        return $this->morphMany(Answer::class, 'answerable');
    }

    public function getAnswerUsingAnsweredby($answeredby)
    {
        return $this->answers()
            ->whereAnsweredby($answeredby)
            ->first();
    }
}
