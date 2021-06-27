<?php

namespace App\DTOs;

use App\Traits\DTOTrait;
use App\YourEdu\Assessment;
use App\YourEdu\AssessmentSection;
use Illuminate\Database\Eloquent\Model;

class AssessmentSectionDTO
{
    use DTOTrait;

    public ?string $assessmentSectionId = null;
    public ?string $name = null;
    public ?string $method = null;
    public ?string $instruction = null;
    public ?string $answerType = null;
    public ?int $position = null;
    public ?int $maxQuestions = null;
    public ?AssessmentSection $assessmentSection = null;
    public ?Assessment $assessment = null;
    public ?Model $addedby = null;
    public bool $random = false;
    public bool $autoMark = false;
    public array $questions = [];
    public array $removedQuestions = [];
    public array $editedQuestions = [];

    public static function createFromArray(array $dataArray, $request = null) : array
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
                questions: property_exists($data, 'questions') ? 
                    array_map(
                        function ($question) use ($request) {
                            return static::mapQuestions($question, $request);
                        }, 
                        $data->questions
                    ) : [],
                removedQuestions: $data->removedQuestions ?? [],
                editedQuestions: property_exists($data, 'editedQuestions') ? 
                    array_map(
                        function ($question) use ($request) {
                            return static::mapQuestions($question, $request);
                        }, 
                        $data->editedQuestions
                    ) : [],
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
        $static->position = (int)$position;
        $static->maxQuestions = (int)$maxQuestions;
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

    private static function mapQuestions($question, $request)
    {
        if (is_null($request)) {
            return $question;
        }
        
        if (! property_exists($question, 'id')) {
            $question->files = null;
            return $question;
        }

        $questionFileId = "question{$question->id}";
        $question->files = $request->$questionFileId;

        return $question;
    }

    public function hasEnoughQuestions()
    {
        return $this->random ? 
            count($this->questions) >= $this->maxQuestions :
            count($this->questions);
    }

    public function hasAppropriateRandomAndMaxQuestionsData()
    {
        if (! $this->random) {
            return true;
        }

        if ($this->maxQuestions) {
            return true;
        }

        return false;
    }
}