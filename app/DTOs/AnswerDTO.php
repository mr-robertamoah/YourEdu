<?php

namespace App\DTOs;

use App\Traits\DTOTrait;
use App\YourEdu\Answer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class AnswerDTO
{
    use DTOTrait;

    public ?string $answerId = null;
    public ?string $answer = null;
    public ?string $itemId = null;
    public ?string $item = null;
    public ?string $accountId = null;
    public ?string $account = null;
    public ?string $answerType = null;
    public ?MarkDTO $markDTO = null;
    public ?Model $answerable = null;
    public ?Model $answeredby = null;
    public ?Answer $answerModel = null;
    public ?int $workId = null;
    public bool $chat = false;
    public bool $checkAnswerType = true;
    public array $possibleAnswerIds = [];
    public array $files = [];
    public array $removedFiles = [];

    public static function createFromArray(array $dataArray): array
    {
        $sections = [];

        foreach ($dataArray as $data) {
            $sections[] = static::createFromData(
                answerId: $data->answerId ?? null,
                answer: $data->answer ?? null,
                workId: $data->workId ?? null,
                answerType: $data->answerType ?
                    strtoupper($data->answerType) : null,
                possibleAnswerIds: $data->possibleAnswerIds ?? [],
            );
        }

        return $sections;
    }

    public static function createFromData(
        $answerId = null,
        $answer = null,
        $workId = null,
        $account = null,
        $accountId = null,
        $item = null,
        $itemId = null,
        $answerType = null,
        $userId = null,
        array $possibleAnswerIds = [],
        array $files = [],
        array $removedFiles = [],
    ) {
        $static = new static();

        $static->userId = $userId;
        $static->files = $files;
        $static->removedFiles = FileDTO::createFromArray($removedFiles);
        $static->answerId = $answerId;
        $static->answer = $answer;
        $static->possibleAnswerIds = $possibleAnswerIds;
        $static->itemId = $itemId;
        $static->item = $item;
        $static->accountId = $accountId;
        $static->account = $account;
        $static->workId = $workId;
        $static->answerType = $answerType ? strtoupper($answerType) : null;

        return $static;
    }

    public static function createFromRequest(Request $request)
    {
        $static = new static();

        $static->userId = $request->user()?->id;
        $static->answerId = $request->answerId;
        $static->answer = $request->answer;
        $static->possibleAnswerIds = $request->possibleAnswerIds ?
            json_decode($request->possibleAnswerIds) : [];
        $static->itemId = $request->itemId;
        $static->item = $request->item;
        $static->accountId = $request->accountId;
        $static->account = $request->account;
        $static->workId = $request->workId;
        $static->answerType = $request->answerType ?
            strtoupper($request->answerType) : null;
        $static->files = $request->file('files') ?: [];
        $static->removedFiles = $request->removedFiles ?
            FileDTO::createFromArray(json_decode($request->removedFiles)) :
            [];

        return $static;
    }

    public function addFiles($files)
    {
        if (is_null($files)) {
            return $this;
        }

        $this->files = $files;

        return $this;
    }

    public function withAnswerable(Model $answerable)
    {
        $clone = clone $this;

        $clone->answerable = $answerable;

        return $clone;
    }

    public function withAnsweredby(Model $answeredby)
    {
        $clone = clone $this;

        $clone->answeredby = $answeredby;

        return $clone;
    }

    public function addMarkData($markData)
    {
        $clone = clone $this;

        if (is_null($markData)) {
            $clone->markDTO = MarkDTO::new();
            return $clone;
        }

        if (is_string($markData)) {
            $markData = json_decode($markData);
        }

        $clone->markDTO = MarkDTO::createFromData(
            remark: $markData->remark,
            score: $markData->score,
            userId: $this->userId,
        );

        return $clone;
    }

    public function dontCheckAnswerType()
    {
        $this->checkAnswerType = false;

        return $this;
    }

    public function refresh()
    {
        $this->answerModel = $this->answerModel->refresh();

        return $this;
    }
}
