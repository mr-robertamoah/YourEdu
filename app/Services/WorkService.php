<?php

namespace App\Services;

use App\DTOs\WorkDTO;
use App\Exceptions\AccountNotFoundException;
use App\Exceptions\WorkException;
use App\Http\Resources\AssessmentMiniResource;
use App\Http\Resources\AssessmentResource;
use App\Traits\ServiceTrait;
use App\User;

class WorkService
{
    use ServiceTrait;

    private function getAssessmentById($workDTO)
    {
        if (is_null($workDTO->assessmentId)) {
            $this->throwWorkException(
                message: "the id of the assessment was not specified",
                data: $workDTO
            );
        }

        return $this->getModel('assessment', $workDTO->assessmentId);
    }

    public function submitWork(WorkDTO $workDTO)
    {
        //get account
        //check account
        
        $assessment = $this->getAssessmentById($workDTO);

        $this->checkLearnerAccessToAssessment($assessment, $workDTO);

        $work = $this->makeWork($workDTO);

        $work = $this->attachWorkToAssessment($workDTO);

        $work = $this->markWork($work, $workDTO);

        $this->notifyMarkers($workDTO);

        $workDTO->methodType = 'created';
        $this->broadcastWork($workDTO);
    }

    private function makeWork(WorkDTO $workDTO)
    {

        return $work;
    }

    private function checkLearnerAccessToAssessment($assessment, $workDTO)
    {
        if (AssessmentService::learnerHasAccessByUserId($assessment, $workDTO->userId)) {
            return;
        }

        $this->throwWorkException(
            message: "sorry, you do not have access to the assessment for which your work is been submitted",
            data: $workDTO;
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
