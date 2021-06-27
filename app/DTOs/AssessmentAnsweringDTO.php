<?php

namespace App\DTOs;

use App\Traits\DTOTrait;
use App\YourEdu\Assessment;
use App\YourEdu\Work;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class AssessmentAnsweringDTO
{
    use DTOTrait;

    public ?Model $answeredby = null;
    public ?Assessment $assessment = null;
    public ?Work $work = null;
    public ?AnswerDTO $answerDTO = null;
    public array $answerDTOs = [];
    public ?string $account = null;
    public ?string $accountId = null;
    public ?string $assessmentId = null;
    public ?string $type = 'all';
    public ?string $status = null;

    public static function createFromRequest(Request $request)
    {
        $self = new static;

        $self->type = $request->type ?: 'all';
        $self->status = $request->status;
        $self->account = $request->account;
        $self->accountId = $request->accountId;
        $self->assessmentId = $request->assessmentId;
        $self->userId = $request->user()?->id;

        if ($self->type === 'all') {

            $self->answerDTOs = self::getAnswerDTOs($request);
        }

        if ($self->type === 'one') {

            $self->answerDTO = self::getAnswerDTO($request);
        }

        return $self;
    }

    private static function getAnswerDTOs($request)
    {
        if (is_null($request->answers)) {
            return [];
        }

        $answers = $request->answers;

        if (is_string($answers)) {
            $answers = (array) json_decode($answers);
        }

        $dto = [];
        foreach ($answers as $answer) {
            $dto[] = self::getAnswerDTOFromAnswer(
                $answer,
                $request->file("answerFile{$answer->questionId}")
            );
        }

        return $dto;
    }

    private static function getAnswerDTO($request)
    {
        if (is_null($request->answer)) {
            return null;
        }

        $answer = $request->answer;

        if (is_string($answer)) {
            $answer = json_decode($answer);
        }

        return self::getAnswerDTOFromAnswer(
            $answer,
            $request->file("answerFile{$answer->questionId}")
        );
    }

    private static function getAnswerDTOFromAnswer($answer, $files = [])
    {
        return AnswerDTO::new()
            ->addData(
                item: 'question',
                itemId: $answer->questionId ?? null,
                answer: $answer->answer ?? null,
                possibleAnswerIds: $answer->possibleAnswerIds ?? [],
                files: ($files && is_array($files)) ?: ($files ? [$files] : [])
            );
    }

    public function setUpAnsweredby()
    {
        $clone = clone $this;

        if ($clone->type === 'one' && $clone->answerDTO) {
            $clone->answerDTO = $clone->answerDTO->withAnsweredby(
                $clone->answeredby
            );

            return $clone;
        }

        if (!count($clone->answerDTOs)) {
            return $clone;
        }

        for ($i = 0; $i < count($clone->answerDTOs); $i++) {
            $clone->answerDTOs[$i] = $clone->answerDTOs[$i]
                ->withAnsweredby($clone->answeredby);
        }

        return $clone;
    }
}
