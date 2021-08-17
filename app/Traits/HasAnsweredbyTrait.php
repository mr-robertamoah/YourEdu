<?php

namespace App\Traits;

use App\YourEdu\Answer;
use App\YourEdu\Assessment;
use App\YourEdu\Mark;

trait HasAnsweredbyTrait
{
    public function answers()
    {
        return $this->morphMany(Answer::class, 'answeredby');
    }

    public function getAnswerById($answerId)
    {
        return $this->answers()
            ->where('id', $answerId)->first();
    }

    public function getTotalScoreOfAnswersForAssessment($assessmentId)
    {
        return $this
            ->getAnswersForAssessment($assessmentId)
            ->loadAvg('marks', 'score')
            ->sum('marks_avg_score');
    }

    public function getAnswersForAssessment($assessmentId)
    {
        return $this->answers()
            ->whereAssessment($assessmentId)
            ->get();
    }

    public function hasAnsweredAllAssessmentQuestions($assessment)
    {
        return $this->answers()
            ->whereAssessment($assessment->id)
            ->count() === $assessment->maxQuestionsCount();
    }

    public function hasNotAnsweredAllAssessmentQuestions($assessmentId)
    {
        return !$this->hasAnsweredAllAssessmentQuestions($assessmentId);
    }

    public function hasAnUnmarkedAnswerForAssessmentAndByMarker($assessmentId, $account)
    {
        return $this->answers()
            ->whereAssessment($assessmentId)
            ->whereNotMarkedby($account)
            ->exists();
    }

    public function doesntHaveAnUnmarkedAnswerForAssessmentAndByMarker($assessmentId, $account)
    {
        return !$this->hasAnUnmarkedAnswerForAssessmentAndByMarker($assessmentId, $account);
    }
}
