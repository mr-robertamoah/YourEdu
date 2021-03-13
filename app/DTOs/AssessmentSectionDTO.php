<?php

namespace App\DTOs;

use App\YourEdu\Assessment;
use App\YourEdu\AssessmentSection;
use Illuminate\Database\Eloquent\Model;

class AssessmentSectionDTO
{
    public string | null $assessmentSectionId;
    public string | null $name;
    public string | null $instruction;
    public string | null $answerType;
    public int | null $position;
    public int | null $maxQuestions;
    public ?AssessmentSection $assessmentSection = null;
    public ?Assessment $assessment = null;
    public ?Model $addedby = null;
    public bool $random = false;
    public bool $autoMark = false;
    public array $questions = [];
    public array $removedQuestions = [];
    public array $editedQuestions = [];

    public static function createFromArray(array $dataArray) : array
    {
        $sections = [];

        foreach ($dataArray as $data) {
            $sections[] = static::createFromData(
                assessmentSectionId: $data->assessmentSectionId ?? null,
                name: $data->name ?? null,
                instruction: $data->instruction ?? null,
                position: $data->position ?? null,
                autoMark: $data->autoMark ?? false,
                maxQuestions: $data->maxQuestions ?? null,
                random: $data->random ?? false,
                answerType: $data->answerType ?? '',
                questions: $data->questions ?? [],
                removedQuestions: $data->removedQuestions ?? [],
                editedQuestions: $data->editedQuestions ?? [],
            );
        }

        return $sections;
    }

    public static function createFromData
    (
        $assessmentSectionId = null, 
        $name = null, 
        $position = null,
        $maxQuestions = null,
        $instruction = null,
        $autoMark = false,
        $random = false,
        $answerType = null,
        $questions = [],
        $removedQuestions = [],
        $editedQuestions = [],
    )
    {
        $static = new static();

        $static->assessmentSectionId = $assessmentSectionId;
        $static->name = $name;
        $static->position = $position;
        $static->maxQuestions = $maxQuestions;
        $static->instruction = $instruction;
        $static->autoMark = $autoMark;
        $static->random = $random;
        $static->answerType = strlen($answerType) ? 
            strtoupper($answerType) : "SHORT_ANSWER";
        $static->questions = QuestionDTO::createFromArray($questions);
        $static->removedQuestions = QuestionDTO::createFromArray($removedQuestions);
        $static->editedQuestions = QuestionDTO::createFromArray($editedQuestions);

        return $static;
    }

    public function withAssessment(Assessment $assessment)
    {
        $clone = clone $this;

        $clone->assessment = $assessment;

        return $clone;
    }

    public function withAddedby(Model $addedby)
    {
        $clone = clone $this;

        $clone->addedby = $addedby;

        return $clone;
    }
}