<?php

namespace App\Services;

use App\DTOs\WorkDTO;
use App\Exceptions\AccountNotFoundException;
use App\Exceptions\WorkException;
use App\Http\Resources\AssessmentMiniResource;
use App\Http\Resources\AssessmentResource;
use App\Traits\ServiceTrait;
use App\User;
use App\YourEdu\Work;

class WorkService
{
    use ServiceTrait;

    private function getAssessmentById($workDTO)
    {
        return $this->getModel('assessment', $workDTO->assessmentId);
    }

    private function setAssessment($workDTO)
    {
        if (is_not_null($workDTO->assessment)) {
            return $workDTO;
        }

        return $workDTO->withAssessment(
            $this->getAssessmentById($workDTO)
        );
    }

    public function submitWork(WorkDTO $workDTO)
    {
        $workDTO = $this->setAssessment($workDTO);

        $this->checkLearnerAccessToAssessment($workDTO);

        $work = $this->makeWork($workDTO);

        $workDTO = $workDTO->withWork($work);

        $work = $this->attachWorkToAccount($workDTO);

        $workDTO->methodType = 'created';

        $this->broadcastWork($workDTO);
    }

    private function broadcastWork($workDTO)
    {
        if ($workDTO->dontBroadcast) {
            return;
        }

        $event = $this->getEvent($workDTO);

        if (is_null($event)) {
            return;
        }

        broadcast($event)->toOthers();
    }

    private function getEvent($dto)
    {
        if ($dto->methodType === 'created') {
            return new WorkCreatedEvent($dto);
        }

        return null;
    }

    private function attachWorkToAccount($workDTO)
    {
        $workDTO->work->addedby()->associate($workDTO->addedby);
        $workDTO->work->save();

        return $workDTO->work->refresh();
    }

    private function makeWork(WorkDTO $workDTO)
    {
        if (is_not_null($workDTO->status)) {
            $this->validateStatus($workDTO);
        }

        return $workDTO->assessment->works()->create([
            'status' => $workDTO->status ? 
                strtoupper($workDTO->status) : Work::PENDING
        ]);
    }

    public function updateWorkStatus($workDTO)
    {
        $this->validateStatus($workDTO);

        $workDTO->work->update([
            'status' => strtoupper($workDTO->status)
        ]);

        return $workDTO->work->refresh();
    }

    private function validateStatus($workDTO)
    {
        if (in_array(strtoupper($workDTO->status), Work::VALID_STATUSES)) {
            return;
        }

        $this->throwWorkException(
            message: "sorry 😞, {$workDTO->status} is not a valid staus for a work",
            data: $workDTO
        );
    }

    private function checkLearnerAccessToAssessment($workDTO)
    {
        if ($workDTO->accessChecked) {
            return;
        }

        if ($workDTO->assessment->learnerHasAccessByUserId($workDTO->userId)) {
            return;
        }

        $this->throwWorkException(
            message: "sorry, you do not have access to the assessment for which your work is been submitted",
            data: $workDTO,
        );
    }

    private function throwWorkException($message, $data = null)
    {
        throw new WorkException(
            message: $message,
            data: $data
        );
    }
}
