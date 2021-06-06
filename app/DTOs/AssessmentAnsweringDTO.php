<?php

namespace App\DTOs;

use App\Traits\DTOTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class AssessmentAnsweringDTO
{
    use DTOTrait;

    public ?Model $answedby = null;
    public ?AnswerDTO $answerDTO = null;
    public array $answerDTOs = [];
    public ?string $account = null;
    public ?string $accountId = null;
    public ?string $userId = null;
    public ?string $type = null;

    public static function createFromRequest(Request $request)
    {
        $self = new static;

        $self->type = $request->type;
        $self->account = $request->account;
        $self->accountId = $request->accountId;
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
            $answer
            $request->file("answerFile{$answer->questionId}")
        );
    }

    private static function getAnswerDTOFromAnswer($answer, $files = [])
    {
        return AnswerDTO::new()
            ->addData(
                item: 'question',
                itemId: $answer->questionId ?? null,
                answer: $answer->answer,
                possibleAnswerId: $answer->possibleAnswerId,
                possibleAnswerIds: $answer->possibleAnswerIds,
                files: $files ?: []
            );
    }
}
