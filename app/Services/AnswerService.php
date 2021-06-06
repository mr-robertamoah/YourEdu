<?php

namespace App\Services;

use App\DTOs\AnswerDTO;
use App\DTOs\MarkDTO;
use App\Exceptions\AnswerException;
use App\Traits\ServiceTrait;
use App\YourEdu\Answer;

class AnswerService
{
    use ServiceTrait;

    public function createAnswer(AnswerDTO $answerDTO)
    {
        $answerDTO = $this->setAnsweredby($answerDTO);

        $answerDTO = $this->setAnswerable($answerDTO);

        $answerDTO = $this->setAnswerType($answerDTO);

        $this->checkPossibleAnswerIds($answerDTO);

        $answer = $this->makeAnswer($answerDTO);

        $answer = $this->addAnswerData($answer, $answerDTO);

        $answer = $this->addPosssibleAnswerData($answer, $answerDTO);

        $answer = $this->addFiles($answer, $answerDTO);

        $this->checkAnswer($answer, $answerDTO);

        $answer = $this->associateAnswerToItem($answer, $answerDTO);

        $answer = $this->autoMark($answer, $answerDTO);

        $this->increasePointsOfAnsweredby($answerDTO);
        
        return $answer;
    }

    private function autoMark($answer, $answerDTO)
    {
        if (! count($answerDTO->possibleAnswerIds)) {
            return $answer;
        }

        $markDTO = $this->setMarkDTO($answer);

        (new MarkService)->autoMarkAnswer($markDTO);

        return $answer->refresh();
    }

    private function setMarkDTO($answer)
    {
        $markDTO = MarkDTO::createFromData(
            state: $answer->answerable->correct_possible_answers == 
                $answer->possible_answer_ids ? 'correct' : 'wrong',
            scoreOver: $answer->answerable->score_over
        );
        
        return $markDTO
            ->withMarkable($answer)
            ->withMarkedby($answer->answerable->addedby);
    }

    private function setAnswerType(AnswerDTO $answerDTO)
    {
        $answerDTO->answerType = $answerDTO->answerable->answer_type;

        return $answerDTO;
    }

    private function addFiles($answer, $answerDTO)
    {
        if ($answerDTO->answerable->doesntRequireFiles()) {
            return $answer;    
        }

        $this->checkFileTypes($answerDTO);

        foreach ($answerDTO->files as $file) {
            
            FileService::createAndAttachFiles(
                account: $answerDTO->answeredby,
                file: $file,
                item: $answer
            );
        }

        return $answer->refresh();
    }

    private function checkPossibleAnswerIds($answerDTO)
    {
        if ($answerDTO->answerable->doesntRequirePossibleAnswers()) {
            return;
        }

        if ($answerDTO->answerable->isTrueOrFalseOptionAnswerType() &&
            count($answerDTO->possibleAnswerIds) === 1) {
            return;
        }
        
        if ($answerDTO->answerable->isArrangeFlowAnswerType() &&
            count($answerDTO->possibleAnswerIds) > 1) {
            return;
        }

        $this->throwAnswerException(
            message: "sorry 😞, you didnt provide the correct number of possible answers for your answer",
            data: $answerDTO
        );
    }

    private function checkFileTypes($answerDTO)
    {
        foreach ($answerDTO->files as $file) {
            $fileType = strtolower($answerDTO->answerable->answer_type);

            if (FileService::uploadedFileHasType($file, $fileType)) {
                continue;
            }

            $this->throwAnswerException(
                message: "please the answer expects that you send {$fileType}. You sent a different type... 😏",
                data: $answerDTO
            );
        }
    }

    private function increasePointsOfAnsweredby($answerDTO)
    {
        if ($answerDTO->chat) {
            return;
        }

        $this->increasePointsOfAccount(
            $answerDTO->answeredby
        );
    }

    private function setAnswerable(AnswerDTO $answerDTO)
    {
        if (is_not_null($answerDTO->answerable)) {
            return $answerDTO;
        }

        return $answerDTO->withAnswerable(
            $this->getModel($answerDTO->item, $answerDTO->itemId)
        );
    }

    private function setAnsweredby(AnswerDTO $answerDTO)
    {
        if (is_not_null($answerDTO->answeredby)) {
            return $answerDTO;
        }

        return $answerDTO->withAnsweredby(
            $this->getModel($answerDTO->account, $answerDTO->accountId)
        );
    }

    private function makeAnswer(AnswerDTO $answerDTO)
    {
        return $answerDTO->answeredby->answers()->create([
            'answer_type' => $answerDTO->answerType,
        ]);
    }

    private function addPosssibleAnswerData($answer, $answerDTO)
    {
        if ($answerDTO->answerable->doesntRequirePossibleAnswers()) {
            return $answer;
        }

        $answer->possible_answer_ids = $answerDTO->possibleAnswerIds;
        $answer->save();

        return $answer;
    }

    private function addAnswerData($answer, $answerDTO)
    {
        if ($answerDTO->checkAnswerType && 
            $answerDTO->answerable->isNotNormalAnswerType()) {
            return $answer;
        }

        $answer->answer = $answerDTO->answer;
        $answer->save();

        return $answer;
    }

    private function checkAnswer(Answer $answer, AnswerDTO $answerDTO)
    {
        if (is_not_null($answer->answer)) {
            return;
        }

        if (is_not_null($answer->possible_answer_ids)) {
            return;
        }
        
        if ($answer->hasFiles()) {
            return;
        }

        $this->throwAnswerException(
            message: "sorry 😞, answer does not have the required data",
            data: $answerDTO
        );
    }

    private function throwAnswerException($message, $data = null)
    {
        throw new AnswerException(
            message: $message,
            data: $data
        );
    }
    
    private function associateAnswerToItem($answer, $answerDTO)
    {
        $answer->answerable()->associate($answerDTO->answerable);
        $answer->save();

        return $answer;
    }
}

?>