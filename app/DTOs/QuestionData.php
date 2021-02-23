<?php

namespace App\Services;

use App\DTOs\PossibleAnswerData;
use Carbon\Carbon;
use Illuminate\Http\Request;

class QuestionData
{
    public string | null $questionId;
    public string | null $question;
    public string | null $state;
    public string | null $hint;
    public string | null $action;
    public int | null $position;
    public int | null $scoreOver;
    public Carbon | null $published;
    public Model | null $questionedby;
    public Model | null $questionable;
    public string | null $answerType;
    public array $possibleAnswers;
    public array $editedPossibleAnswers;
    public array $removedPossibleAnswers;

    public static function createFromArray(array $dataArray) : array
    {
        $questions = [];

        foreach ($dataArray as $data) {
            $questions[] = static::createFromData(
                questionId: $data->questionId ?? null,
                question: $data->question ?? null,
                state: $data->state ?? null,
                scoreOver: $data->score ?? null,
                position: $data->position ?? null,
                published: $data->published ?? null,
                hint: $data->hint ?? null,
                answerType: $data->answerType ? 
                    strtoupper($data->answerType) : null,
                possibleAnswers: $data->possibleAnswers ?? [],
                removedPossibleAnswers: $data->removedPossibleAnswers ?? [],
                editedPossibleAnswers: $data->editedPossibleAnswers ?? [],
            );
        }

        return $questions;
    }

    public static function createFromData
    (
        $questionId = null, 
        $question = null, 
        $position = null,
        $state = null,
        $published = false,
        $hint = null,
        $scoreOver = null,
        $questionedby = null,
        $questionable = null,
        $answerType = null,
        array $possibleAnswers,
        array $removedPossibleAnswers,
        array $editedPossibleAnswers,
    )
    {
        $static = new static();

        $static->questionId = $questionId;
        $static->question = $question;
        $static->position = $position;
        $static->state = $state;
        $static->published = Carbon::parse($published);
        $static->hint = $hint;
        $static->$questionable = $questionable;
        $static->$questionedby = $questionedby;
        $static->scoreOver = $scoreOver;
        $static->answerType = $answerType ? strtoupper($answerType) : null;
        $static->possibleAnswers = PossibleAnswerData::createFromArray($possibleAnswers);
        $static->editedPossibleAnswers = PossibleAnswerData::createFromArray($editedPossibleAnswers);
        $static->removedPossibleAnswers = PossibleAnswerData::createFromArray($removedPossibleAnswers);

        return $static;
    }

    public static function createFromRequest
    (
        Request $request,
    )
    {
        $static = new static();

        $static->questionId = $request->questionId;
        $static->question = $request->question;
        $static->state = $request->state;
        $static->published = $request->published ?
            Carbon::parse($request->published) : null;
        $static->hint = $request->hint;
        $static->answerType = $request->answerType ? 
            strtoupper($request->answerType) : null;
        $static->scoreOver = (int)$request->score;
        if ($static->scoreOver > 100) {
            $static->scoreOver = 100;
        } else if ($static->scoreOver < 5) {
            $static->scoreOver = 5;
        }
        $static->possibleAnswers = !is_null(json_decode($request->possibleAnswers)) ? 
            PossibleAnswerData::createFromArray(
                json_decode($request->possibleAnswers)
            ) : [];

        return $static;
    }
}