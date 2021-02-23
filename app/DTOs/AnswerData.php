<?php

namespace App\DTOs;

use Illuminate\Database\Eloquent\Model;

class AnswerData
{
    public string | null $answerId;
    public string | null $answer;
    public string | null $answerType;
    public Model | null $answerable;
    public Model | null $answeredby;
    public int | null $possibleAnswerId;
    public int | null $workId;
    public array $possibleAnswerIds;

    public static function createFromArray(array $dataArray) : array
    {
        $sections = [];

        foreach ($dataArray as $data) {
            $sections[] = static::createFromData(
                answerId: $data->answerId ?? null,
                answer: $data->answer ?? null,
                workId: $data->workId ?? null,
                possibleAnswerId: $data->possibleAnswerId ?? null,
                answerType: $data->answerType ? 
                    strtoupper($data->answerType) : null,
                possibleAnswerIds: $data->possibleAnswerIds ?? [],
            );
        }

        return $sections;
    }

    public static function createFromData
    (
        $answerId = null, 
        $answer = null,
        $workId = null,
        $possibleAnswerId = null,
        $answerType = null,
        array $possibleAnswerIds,
    )
    {
        $static = new static();

        $static->answerId = $answerId;
        $static->answer = $answer;
        $static->possibleAnswerIds = $possibleAnswerIds;
        $static->workId = $workId;
        $static->possibleAnswerId = $possibleAnswerId;
        $static->answerType = $answerType ? strtoupper($answerType) : null;

        return $static;
    }
}