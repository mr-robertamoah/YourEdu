<?php

namespace App\Traits;

use App\YourEdu\Answer;

trait AccountAnswersTrait
{
    public function answers()
    {
        return $this->morphMany(Answer::class,'answeredby');
    }

    public function getAnswerById($answerId)
    {
        return $this->answers()
            ->where('id', $answerId)->first();
    }

}
