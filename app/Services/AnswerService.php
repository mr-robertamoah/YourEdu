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

        $answerDTO = $answerDTO->withAnswerModel(
            $this->makeAnswer($answerDTO)
        );

        $answerDTO = $this->enterAnswerData($answerDTO);

        $answerDTO = $this->associateAnswerToItem($answerDTO);

        $answerDTO = $this->autoMark($answerDTO);

        $this->updatePointsOfAnsweredby($answerDTO);

        return $answerDTO->answerModel; //deal with deleting files when throwing exceptions
    }

    private function enterAnswerData($answerDTO)
    {
        $answerDTO = $this->addAnswerData($answerDTO);

        $answerDTO = $this->addPosssibleAnswerData($answerDTO);

        $answerDTO = $this->addFiles($answerDTO);

        $this->checkAnswer($answerDTO);

        return $answerDTO;
    }

    public function createOnlyNewAnswer(AnswerDTO $answerDTO)
    {
        $answerDTO = $this->setAnsweredby($answerDTO);

        $answerDTO = $this->setAnswerable($answerDTO);

        $answerDTO = $this->setAnswerModel($answerDTO);

        $answerDTO = $answerDTO->addData(
            userId: $answerDTO->answeredby->user_id
        );

        if ($answerDTO->answerModel) {
            $this->deleteAnswer($answerDTO);
        }

        return $this->createAnswer($answerDTO);
    }

    public function updateAnswer(AnswerDTO $answerDTO)
    {
        ray($answerDTO)->green();
        $answerDTO = $this->setAnsweredby($answerDTO);

        $this->checkAccountOwnership($answerDTO->answeredby, $answerDTO->userId);

        $answerDTO = $this->setAnswerModel($answerDTO);

        $answerDTO = $this->setAnswerable($answerDTO);

        $this->ensureIsAnsweredby($answerDTO);

        $this->ensureIsNotMarked($answerDTO);

        $this->setWorkStatus($answerDTO);

        $answerDTO = $this->enterAnswerData($answerDTO);

        return $answerDTO->answerModel;
    }

    private function setWorkStatus($answerDTO)
    {
        if ($answerDTO->answerModel->isNotForAssessment()) {
            return;
        }

        (new AssessmentService)
            ->ensureIsNotDue(
                $answerDTO->answerModel->getAssessment(),
                $answerDTO
            )
            ->ensureHasMoreTime(
                $answerDTO->answerModel->getAssessment(),
                $answerDTO
            );

        $answerDTO->answerModel->setWorkStatusToPending();
    }

    public function deleteAnswer(AnswerDTO $answerDTO)
    {
        $answerDTO = $this->setAnsweredby($answerDTO);

        $this->checkAccountOwnership($answerDTO->answeredby, $answerDTO->userId);

        $answerDTO = $this->setAnswerable($answerDTO);

        $answerDTO = $this->setAnswerModel($answerDTO);

        $this->ensureIsAnsweredby($answerDTO);

        $this->setWorkStatus($answerDTO);

        $this->deleteFiles($answerDTO);

        return $answerDTO->answerModel->delete();
    }

    private function deleteFiles($answerDTO)
    {
        if ($answerDTO->answerModel->doesntHaveFiles()) {
            return;
        }

        FileService::deleteYourEduItemFiles($answerDTO->answerModel);
    }

    private function checkFiles($answerDTO)
    {
        if ($answerDTO->answerModel->doesntHaveFiles()) {
            return;
        }

        FileService::deleteYourEduItemFiles($answerDTO->answerModel);
    }

    private function ensureIsAnsweredby($answerDTO)
    {
        if ($answerDTO->answerModel->isAnsweredby($answerDTO->answeredby)) {
            return;
        }

        $this->throwAnswerException(
            message: "sorry 😞, your {$answerDTO->answeredby->accountType} account doesn't own this answer.",
            data: $answerDTO
        );
    }

    private function ensureIsNotMarked($answerDTO)
    {
        if ($answerDTO->answerModel->isNotMarked()) {
            return;
        }

        $this->throwAnswerException(
            message: "sorry 😞, you cannot update this answer because it has already been marked.",
            data: $answerDTO
        );
    }

    private function setAnswerModel($answerDTO)
    {
        if ($answerDTO->answerModel) {
            return $answerDTO;
        }

        if ($answerDTO->answerId) {
            return $answerDTO->withAnswerModel(
                $this->getModel('answer', $answerDTO->answerId)
            );
        }

        if ($answerDTO->answerable) {
            return $answerDTO->withAnswerModel(
                $answerDTO->answerable->getAnswerUsingAnsweredby($answerDTO->answeredby)
            );
        }

        return $answerDTO;
    }

    private function autoMark($answerDTO)
    {
        if (!count($answerDTO->possibleAnswerIds)) {
            return $answerDTO;
        }

        $markDTO = $this->setMarkDTO($answerDTO->answerModel);

        (new MarkService)->autoMarkAnswer($markDTO);

        return $answerDTO->refresh();
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

    private function addFiles($answerDTO)
    {
        if ($answerDTO->answerable->doesntRequireFiles()) {
            return $answerDTO;
        }

        $this->checkFileTypes($answerDTO);

        foreach ($answerDTO->files as $file) {

            FileService::createAndAttachFiles(
                account: $answerDTO->answeredby,
                file: $file,
                item: $answerDTO->answerModel
            );
        }

        return $answerDTO->refresh();
    }

    private function checkPossibleAnswerIds($answerDTO)
    {
        if ($answerDTO->answerable->doesntRequirePossibleAnswers()) {
            return;
        }

        if (
            $answerDTO->answerable->isTrueOrFalseOptionAnswerType() &&
            count($answerDTO->possibleAnswerIds) === 1
        ) {
            return;
        }

        if (
            $answerDTO->answerable->isArrangeFlowAnswerType() &&
            count($answerDTO->possibleAnswerIds) === $answerDTO->answerable->possibleAnswers()->count()
        ) {
            return;
        }

        $this->throwAnswerException(
            message: "sorry 😞, you didnt provide the correct number of possible answers for your answer",
            data: $answerDTO
        );
    }

    private function checkFileTypes($answerDTO)
    {
        $fileType = strtolower($answerDTO->answerable->answer_type);

        foreach ($answerDTO->files as $file) {

            if (FileService::uploadedFileHasType($file, $fileType)) {
                continue;
            }

            $this->throwAnswerException(
                message: "please the answer expects that you send {$fileType}. You sent a different type... 😏",
                data: $answerDTO
            );
        }
    }

    private function updatePointsOfAnsweredby($answerDTO, $type = 'increase')
    {
        if ($answerDTO->chat) {
            return;
        }

        $method = "{$type}PointsOfAccount";

        $this->$method(
            $answerDTO->answeredby
        );
    }

    private function setAnswerable(AnswerDTO $answerDTO)
    {
        if (is_not_null($answerDTO->answerable)) {
            return $answerDTO;
        }

        if ($answerDTO->answerModel?->answerable) {
            return $answerDTO->withAnswerable(
                $answerDTO->answerModel->answerable
            );
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

    private function addPosssibleAnswerData($answerDTO)
    {
        $this->checkPossibleAnswerIds($answerDTO);

        $answerDTO->answerModel->possible_answer_ids = array_unique($answerDTO->possibleAnswerIds);
        $answerDTO->answerModel->save();

        return $answerDTO->refresh();
    }

    private function addAnswerData($answerDTO)
    {
        if (
            $answerDTO->checkAnswerType &&
            $answerDTO->answerable->isNotNormalAnswerType()
        ) {
            return $answerDTO;
        }

        $answerDTO->answerModel->answer = $answerDTO->answer;
        $answerDTO->answerModel->save();

        return $answerDTO->refresh();
    }

    private function checkAnswer(AnswerDTO $answerDTO)
    {
        if (is_not_null($answerDTO->answerModel->answer)) {
            return;
        }

        if (is_not_null($answerDTO->answerModel->possible_answer_ids)) {
            return;
        }

        if ($answerDTO->answerModel->hasFiles()) {
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

    private function associateAnswerToItem($answerDTO)
    {
        $answerDTO->answerModel->answerable()->associate($answerDTO->answerable);
        $answerDTO->answerModel->save();

        return $answerDTO->refresh();
    }
}
