<?php

namespace App\Services;

use App\DTOs\MarkDTO;
use App\Exceptions\MarkException;
use App\Traits\ServiceTrait;
use App\YourEdu\Mark;

class MarkService
{
    use ServiceTrait;

    const MARKER_CLASSES = [
        'App\\YourEdu\\Facilitator',
        'App\\YourEdu\\Professional',
    ];

    public function createMark(MarkDTO $markDTO)
    {
        $markDTO = $this->setMarkedby($markDTO);

        $markDTO = $this->setMarkable($markDTO);

        $this->ensureNotMarkedByAnsweredby($markDTO);

        $markDTO = $this->setScoreOver($markDTO);

        $this->checkScore($markDTO);

        $markDTO = $this->setState($markDTO);

        $this->ensureNotAlreadyMarked($markDTO);

        $mark = $this->addMark($markDTO);

        $this->checkMark($mark, $markDTO);

        $mark = $this->associateMarkToMarkable($mark, $markDTO);

        $this->increasePointsOfMarkedby($markDTO);

        return $mark;
    }

    public function createOnlyNewMark(MarkDTO $markDTO)
    {
        $markDTO = $this->setMarkedby($markDTO);

        $markDTO = $this->setMarkable($markDTO);

        $markDTO = $this->setMark($markDTO);

        $markDTO = $markDTO->addData(
            userId: $markDTO->markedby->user_id
        );

        if ($markDTO->mark) {
            $this->deleteMark($markDTO);
        }

        return $this->createMark($markDTO);
    }

    public function updateMark(MarkDTO $markDTO)
    {
        $markDTO = $this->setMark($markDTO);

        $this->ensureIsMarkedby($markDTO);

        return $this->editMark($markDTO);
    }

    private function editMark(MarkDTO $markDTO)
    {
        $data = [];

        if ($markDTO->score) {
            $data['score'] = $markDTO->score;
        }

        if ($markDTO->remark) {
            $data['remark'] = $markDTO->remark;
        }

        $markDTO->mark->update($data);

        return $markDTO->mark->refresh();
    }

    public function deleteMark(MarkDTO $markDTO)
    {
        $markDTO = $this->setMark($markDTO);

        $this->ensureIsMarkedby($markDTO);

        $markDTO->mark->delete();
    }

    private function ensureIsMarkedby($markDTO)
    {
        if ($markDTO->mark->isMarkedbyUser($markDTO->userId)) {
            return;
        }

        $this->throwMarkException(
            message: "sorry 😞, you do not own this mark.",
            data: $markDTO
        );
    }

    private function setMark($markDTO)
    {
        if ($markDTO->mark) {
            return $markDTO;
        }

        if ($markDTO->markId) {
            return $markDTO->withMark(
                $this->getModel('mark', $markDTO->markId)
            );
        }

        if ($markDTO->markable) {
            return $markDTO->withMark(
                $markDTO->markable->getMarkUsingMarkedby($markDTO->markedby)
            );
        }

        return $markDTO;
    }

    private function ensureNotMarkedByAnsweredby($markDTO)
    {
        if (is_null($markDTO->markable->answeredby)) {
            return;
        }

        if ($markDTO->markable->answeredby->isNotAccount($markDTO->markedby)) {
            return;
        }

        $type = class_basename_lower($markDTO->markable);
        $this->throwMarkException(
            message: "you cannot mark your own $type 😏",
            data: $markDTO
        );
    }

    private function setState($markDTO)
    {
        if ($markDTO->score == $markDTO->scoreOver) {
            $markDTO->state = 'correct';
        }

        if (
            $markDTO->score < $markDTO->scoreOver &&
            $markDTO->score > 0
        ) {
            $markDTO->state = 'partial';
        }

        if ($markDTO->score == 0) {
            $markDTO->state = 'wrong';
        }

        return $markDTO;
    }

    private function checkScore($markDTO)
    {
        if ($markDTO->score <= $markDTO->scoreOver) {
            return;
        }

        $this->throwMarkException(
            message: "you cannot score this answer above {$markDTO->scoreOver} 😏",
            data: $markDTO
        );
    }

    private function ensureNotAlreadyMarked(MarkDTO $markDTO)
    {
        if ($markDTO->markable->isNotMarkedbyUser($markDTO->userId)) {
            return;
        }

        $this->throwMarkException(
            message: "charlie, you can't mark the same item twice 😏",
            data: $markDTO
        );
    }

    private function setScoreOver(MarkDTO $markDTO)
    {
        if ($markDTO->scoreOver) {
            return $markDTO;
        }

        return $markDTO->addData(
            scoreOver: $markDTO->markable?->answerable->score_over
        );
    }

    private function increasePointsOfMarkedby($markDTO)
    {
        if ($markDTO->chat) {
            return;
        }

        $this->increasePointsOfAccount(
            $markDTO->markedby
        );
    }

    private function associateMarkToMarkable($mark, $markDTO)
    {
        $mark->markable()->associate($markDTO->markable);
        $mark->save();

        return $mark;
    }

    private function addMark(MarkDTO $markDTO)
    {
        return  $markDTO->markedby->marks()->create([
            'remark' => $markDTO->remark,
            'state' => strtoupper($markDTO->state),
            'score' => (int) $markDTO->score,
            'score_over' => $markDTO->scoreOver,
        ]);
    }

    private function setMarkable(MarkDTO $markDTO)
    {
        if (is_not_null($markDTO->markable)) {
            return $markDTO;
        }

        return $markDTO->withMarkable(
            $this->getModel($markDTO->item, $markDTO->itemId)
        );
    }

    private function setMarkedby(MarkDTO $markDTO)
    {
        if (is_not_null($markDTO->markedby)) {
            return $markDTO;
        }

        return $markDTO->withMarkedby(
            $this->getModel($markDTO->account, $markDTO->accountId)
        );
    }

    private function checkMark(Mark $mark, MarkDTO $markDTO)
    {
        if (is_not_null($mark)) {
            return;
        }

        $this->throwMarkException(
            message: "sorry 😞, creation of mark failed",
            data: $asnwerDTO
        );
    }

    private function throwMarkException($message, $data = null)
    {
        throw new MarkException(
            message: $message,
            data: $data
        );
    }

    public function autoMarkAnswer(MarkDTO $markDTO)
    {
        if ($markDTO->markable->isNotAutoMarkable()) {
            return;
        }

        if ($this->isAnswerCorrect($markDTO)) {
            $markDTO =  $this->setAsCorrect($markDTO);
        }

        if ($this->isAnswerWrong($markDTO)) {
            $markDTO =  $this->setAsWrong($markDTO);
        }

        return $this->addMark($markDTO);
    }

    private function isAnswerCorrect(MarkDTO $markDTO)
    {
        return $markDTO->state === 'correct';
    }

    private function isAnswerWrong(MarkDTO $markDTO)
    {
        return $markDTO->state === 'wrong';
    }

    private function setAsCorrect(MarkDTO $markDTO)
    {
        $markDTO->score = $markDTO->scoreOver;

        return $markDTO;
    }

    private function setAsWrong(MarkDTO $markDTO)
    {
        $markDTO->score = 0;

        return $markDTO;
    }
}
