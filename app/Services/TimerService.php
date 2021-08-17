<?php

namespace App\Services;

use App\DTOs\TimerDTO;
use App\Exceptions\TimerException;
use App\Traits\ServiceTrait;
use App\YourEdu\Timer;

class TimerService
{
    use ServiceTrait;

    public function createTimer(TimerDTO $timerDTO)
    {
        $timerDTO = $this->setAddedby($timerDTO);

        $timerDTO = $this->setTimeable($timerDTO);

        $this->ensureTimeableHasDuration($timerDTO);

        if ($timerDTO->timeable->hasTimerAddedby($timerDTO->addedby)) {
            return $timerDTO->timeable->timerAddedby($timerDTO->addedby);
        }

        $timer = $this->makeTimer($timerDTO);

        $timerDTO = $timerDTO->withTimer($timer);

        $timer = $this->attachAddedbyToTimer($timerDTO);

        $timer = $this->attachTimeableToTimer($timerDTO);

        return $timer->refresh();
    }

    public function ensureTimeableHasDuration($timerDTO)
    {
        if ($timerDTO->timeable->duration) {
            return;
        }

        $item = class_basename_lower($timerDTO->timeable);

        $this->throwTimerException(
            message: "sorry 😞, this {$item} must have a duration before you can create a timer.",
            data: $timerDTO
        );
    }

    public function updateTimer(TimerDTO $timerDTO)
    {
        $timer = $this->getModel('timer', $timerDTO->timerId);

        $timerDTO = $timerDTO->withTimer($timer);

        $this->ensureIsAddedby($timerDTO);

        $timer = $this->addEndTime($timerDTO);

        return $timer;
    }

    private function addEndTime($timerDTO)
    {
        $timerDTO->timer->update([
            'end_time' => $timerDTO->time->toDateTimeString()
        ]);

        return $timerDTO->timer;
    }

    public function ensureIsAddedby($timerDTO)
    {
        if ($timerDTO->timer->isAddedbyUser($timerDTO->userId)) {
            return;
        }

        $this->throwTimerException(
            message: "sorry 😞, you are accessing a wrong timer.",
            data: $timerDTO
        );
    }

    private function throwTimerException($message, $data = null)
    {
        throw new TimerException(
            message: $message,
            data: $data
        );
    }

    private function attachAddedbyToTimer($timerDTO)
    {
        $timerDTO->timer->addedby()->associate($timerDTO->addedby);
        $timerDTO->timer->save();

        return $timerDTO->timer;
    }

    private function attachTimeableToTimer($timerDTO)
    {
        $timerDTO->timer->timeable()->associate($timerDTO->timeable);
        $timerDTO->timer->save();

        return $timerDTO->timer;
    }

    private function makeTimer($timerDTO)
    {
        return Timer::create([
            'start_time' => $timerDTO->time->toDateTimeString(),
            'end_time' => $timerDTO->time
                ->addMinutes($timerDTO->timeable->duration)->toDateTimeString()
        ]);
    }

    public function setAddedby($timerDTO)
    {
        if ($timerDTO->addedby) {
            return $timerDTO;
        }

        return $timerDTO->withAddedby(
            $this->getModel($timerDTO->account, $timerDTO->accountId)
        );
    }

    public function setTimeable($timerDTO)
    {
        if ($timerDTO->timeable) {
            return $timerDTO;
        }

        return $timerDTO->withTimeable(
            $this->getModel($timerDTO->item, $timerDTO->itemId)
        );
    }
}
