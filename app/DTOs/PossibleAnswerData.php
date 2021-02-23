<?php

namespace App\DTOs;

class PossibleAnswerData
{
    public string | null $possibleAnswerId;
    public string | null $option;

    public static function createFromArray(array $dataArray) : array
    {
        $possibleAnswers = [];

        foreach ($dataArray as $data) {
            $possibleAnswers[] = static::createFromData(
                possibleAnswerId: $data->possibleAnswerId ?? null,
                option: $data->option ?? null,
            );
        }

        return $possibleAnswers;
    }

    public static function createFromData
    (
        $possibleAnswerId = null, 
        $option = null,
    )
    {
        $static = new static();

        $static->possibleAnswerId = $possibleAnswerId;
        $static->option = $option;

        return $static;
    }
}