<?php

namespace App\DTOs;

use App\DTOs\PossibleAnswerDTO;
use App\Traits\DTOTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class QuestionDTO
{
    use DTOTrait;

    public ?string $questionId = null;
    public ?string $body = null;
    public ?string $state = null;
    public ?string $hint = null;
    public ?string $account = null;
    public ?string $accountId = null;
    public ?string $action = null;
    public ?string $method = null;
    public bool $autoMark = false;
    public ?int $position = null;
    public ?float $scoreOver = null;
    public ?AnswerDTO $answerDTO = null;
    public ?Carbon $publishedAt = null;
    public ?Model $addedby = null;
    public ?Model $question = null;
    public ?Model $questionable = null;
    public ?Model $questionedby = null;
    public ?string $answerType = null;
    public bool $checkAuthorization = false;
    public bool $mustHaveScoreOver = false;
    public array $files = [];
    public array $removedFiles = [];
    public ?array $possibleAnswers = [];
    public ?array $editedPossibleAnswers = [];
    public ?array $removedPossibleAnswers = [];
    public ?array $correctPossibleAnswers = [];

    public static function createFromArray(
        array $dataArray
    ): array {
        $questions = [];

        foreach ($dataArray as $data) {
            $questions[] = static::createFromData(
                questionId: $data->questionId ?? null,
                body: $data->body ?? null,
                state: $data->state ?? null,
                scoreOver: $data->scoreOver ?? null,
                position: $data->position ?? null,
                autoMark: $data->autoMark ?? false,
                publishedAt: $data->published ?? null,
                hint: $data->hint ?? '',
                answerType: $data->answerType ?? "",
                files: $data->files ?? [],
                removedFiles: $data->removedFiles ?? [],
                possibleAnswers: $data->possibleAnswers ?? [],
                removedPossibleAnswers: $data->removedPossibleAnswers ?? [],
                editedPossibleAnswers: $data->editedPossibleAnswers ?? [],
                correctPossibleAnswers: $data->correctPossibleAnswers ?? [],
            );
        }

        return $questions;
    }

    public static function createFromData(
        array $possibleAnswers = [],
        array $correctPossibleAnswers = [],
        array $removedPossibleAnswers = [],
        array $editedPossibleAnswers = [],
        $questionId = null,
        $body = null,
        $position = null,
        $state = null,
        $autoMark = false,
        $publishedAt = null,
        $hint = null,
        $accountId = null,
        $account = null,
        $userId = null,
        $scoreOver = null,
        $answerType = null,
        array $files = [],
        array $removedFiles = [],
    ) {
        $static = new static();

        $static->questionId = $questionId;
        $static->body = $body;
        $static->autoMark = $autoMark;
        $static->position = (int)$position;
        $static->state = $state;
        $static->publishedAt = Carbon::parse($publishedAt);
        $static->userId = $userId;
        $static->account = $account;
        $static->accountId = $accountId;
        $static->hint = $hint;
        $static->files = $files;
        $static->removedFiles = FileDTO::createFromArray($removedFiles);
        $static->scoreOver = (float)$scoreOver;
        $static->answerType = $answerType ? strtoupper($answerType) : null;
        $static->possibleAnswers = PossibleAnswerDTO::createFromArray($possibleAnswers);
        $static->editedPossibleAnswers = PossibleAnswerDTO::createFromArray($editedPossibleAnswers);
        $static->removedPossibleAnswers = PossibleAnswerDTO::createFromArray($removedPossibleAnswers);
        $static->correctPossibleAnswers = PossibleAnswerDTO::createFromArray($correctPossibleAnswers);

        return $static;
    }

    public static function createFromRequest(
        Request $request,
    ) {
        $static = new static();

        $static->questionId = $request->questionId;
        $static->body = $request->body;
        $static->state = $request->state;
        $static->published = $request->published ?
            Carbon::parse($request->published) : null;
        $static->hint = $request->hint;
        $static->answerType = $request->answerType ?
            strtoupper($request->answerType) : null;
        $static->scoreOver = (float)$request->scoreOver;
        if ($static->scoreOver > 100) {
            $static->scoreOver = 100;
        } else if ($static->scoreOver < 5) {
            $static->scoreOver = 5;
        }
        $static->possibleAnswers = !is_null(json_decode($request->possibleAnswers)) ?
            PossibleAnswerDTO::createFromArray(
                json_decode($request->possibleAnswers)
            ) : [];
        $static->editedPossibleAnswers = !is_null(json_decode($request->editedPossibleAnswers)) ?
            PossibleAnswerDTO::createFromArray(
                json_decode($request->editedPossibleAnswers)
            ) : [];
        $static->removedPossibleAnswers = !is_null(json_decode($request->removedPossibleAnswers)) ?
            PossibleAnswerDTO::createFromArray(
                json_decode($request->removedPossibleAnswers)
            ) : [];
        $static->files = $request->hasFile('typeFiles') ?
            $request->file('typeFiles') : [];
        $static->removedFiles = $request->removedTypeFiles ?
            FileDTO::createFromArray(
                json_decode($request->removedTypeFiles)
            ) : [];

        return $static;
    }

    public function resetFiles()
    {
        $clone = clone $this;

        $clone->files = [];

        return $clone;
    }

    public function addAnswerData($answerData)
    {
        $clone = clone $this;

        if (is_null($answerData)) {
            $clone->answerDTO = AnswerDTO::new();
            return $clone;
        }

        if (is_string($answerData)) {
            $answerData = json_decode($answerData);
        }

        $clone->answerDTO = AnswerDTO::createFromData(
            answer: $answerData->answer ?? null,
            possibleAnswerIds: $answerData->possibleAnswerIds ?? [],
        );

        return $clone;
    }

    public function addAnswerFiles($files)
    {
        if (is_null($files)) {
            return $this;
        }

        if (is_null($this->answerDTO)) {
            $this->answerDTO = AnswerDTO::new();
        }

        $this->answerDTO->files = $files;

        return $this;
    }
}
