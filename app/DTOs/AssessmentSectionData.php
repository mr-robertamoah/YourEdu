<?php

namespace App\Services;

class AssessmentSectionData
{
    public string | null $sectionId;
    public string | null $name;
    public string | null $instruction;
    public string | null $answerType;
    public int | null $position;
    public int | null $maxQuestions;
    public bool $random;
    public bool $autoMark;
    public array $questions = [];
    public array $removedQuestions = [];
    public array $editedQuestions = [];

    public static function createFromArray(array $dataArray) : array
    {
        $sections = [];

        foreach ($dataArray as $data) {
            $sections[] = static::createFromData(
                sectionId: $data->sectionId ?? null,
                name: $data->name ?? null,
                instruction: $data->instruction ?? null,
                position: $data->position ?? null,
                autoMark: $data->autoMark ?? null,
                maxQuestions: $data->maxQuestions ?? null,
                random: $data->random ?? null,
                answerType: $data->answerType ? 
                    strtoupper($data->answerType) : null,
                questions: $data->questions ?? [],
                removedQuestions: $data->removedQuestions ?? [],
                editedQuestions: $data->editedQuestions ?? [],
            );
        }

        return $sections;
    }

    public static function createFromData
    (
        $sectionId = null, 
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

        $static->sectionId = $sectionId;
        $static->name = $name;
        $static->position = $position;
        $static->maxQuestions = $maxQuestions;
        $static->instruction = $instruction;
        $static->autoMark = $autoMark;
        $static->random = $random;
        $static->answerType = $answerType ? strtoupper($answerType) : null;
        $static->questions = QuestionData::createFromArray($questions);
        $static->removedQuestions = QuestionData::createFromArray($removedQuestions);
        $static->editedQuestions = QuestionData::createFromArray($editedQuestions);

        return $static;
    }
}