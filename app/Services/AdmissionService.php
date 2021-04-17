<?php

namespace App\Services;

use App\DTOs\AdmissionDTO;
use App\Exceptions\AdmissionException;
use App\Traits\ServiceTrait;
use App\YourEdu\Admission;
use Illuminate\Support\Str;

class AdmissionService
{
    use ServiceTrait;

    const VALIDADMISSIONRELATEDACCOUNTTYPES = ['learner', 'school'];
    const VALIDADMISSIONTYPES = ['virtual', 'traditional'];
    const VALIDADMISSIONSTATES = ['pending', 'accepted', 'declined'];

    public function createAdmission(AdmissionDTO $admissionDTO)
    {
        $this->checkAdmissionData($admissionDTO);

        $admissionDTO = $this->setAddedby($admissionDTO);

        $this->checkAddedbyAccountType($admissionDTO);

        $admission = $this->addAdmission($admissionDTO);

        $this->checkAdmission($admission, $admissionDTO);

        $admissionDTO = $admissionDTO->withAdmission($admission);

        $admission = $this->addRelationshipsToAdmission($admissionDTO);

        return $admission;
    }

    private function checkAdmissionData($requestDTO)
    {
        if (in_array($requestDTO->state, self::VALIDADMISSIONSTATES)) {
            return;
        }
        
        if (in_array($requestDTO->type, self::VALIDADMISSIONTYPES)) {
            return;
        }

        $this->throwAccountNotFoundException(
            message: "you did not provide valid state or type for the creation of the admission",
            data: $requestDTO
        );
    }

    private function checkAddedbyAccountType($admissionDTO)
    {
        if (in_array($admissionDTO->addedby->accountType, self::VALIDADMISSIONRELATEDACCOUNTTYPES)) {
            return;
        }

        $this->throwAccountNotFoundException(
            message: "{$admissionDTO->addedby->accountType} type of account cannot add an admission",
            data: $admissionDTO
        );
    }

    public function addRelationshipsToAdmission($admissionDTO)
    {
        $admission = $this->attachToSchool($admissionDTO);

        $admission = $this->attachToLearner($admissionDTO);

        $admission = $this->attachToGrade($admissionDTO);

        return $admission;
    }

    private function attachToSchool($admissionDTO)
    {
        $school = null;

        if ($admissionDTO->school) {
            $school = $admissionDTO->school;
        }

        if ($admissionDTO->schoolId) {
            $school = $this->getModel('school', $admissionDTO->schoolId);
        }

        if (is_null($school)) {
            return $admissionDTO->admission;
        }

        $admissionDTO->admission->school_id = $school->id;
        $admissionDTO->admission->save();

        return $admissionDTO->admission;
    }

    private function attachToGrade($admissionDTO)
    {
        $grade = null;

        if ($admissionDTO->grade) {
            $grade = $admissionDTO->grade;
        }

        if ($admissionDTO->gradeId) {
            $grade = $this->getModel('grade', $admissionDTO->gradeId);
        }

        if (is_null($grade)) {
            return $admissionDTO->admission;
        }

        $admissionDTO->admission->grade_id = $grade->id;
        $admissionDTO->admission->save();

        return $admissionDTO->admission;
    }

    private function attachToLearner($admissionDTO)
    {
        $learner = null;

        if ($admissionDTO->learner) {
            $learner = $admissionDTO->learner;
        }

        if ($admissionDTO->learnerId) {
            $learner = $this->getModel('learner', $admissionDTO->learnerId);
        }

        if (is_null($learner)) {
            return $admissionDTO->admission;
        }

        $admissionDTO->admission->learner_id = $learner->id;
        $admissionDTO->admission->save();

        return $admissionDTO->admission;
    }

    private function checkAdmission($admission, $admissionDTO)
    {
        if (is_not_null($admission)) {
            return;
        }

        $this->throwAdmissionException(
            message: "failed to create the admission",
            data: $admissionDTO
        );
    }

    private function throwAdmissionException($message, $data = null)
    {
        throw new AdmissionException(
            message: $message,
            data: $data
        );
    }

    private function addAdmission(AdmissionDTO $admissionDTO) : Admission
    {
        $admission = $admissionDTO->addedby->addedAdmissions()->create([
            'state' => Str::upper($admissionDTO->state),
            'type' => Str::upper($admissionDTO->type),
        ]);

        return $admission;
    }

    private function setAddedby(AdmissionDTO $admissionDTO)
    {
        if ($admissionDTO->addedby) {
            return $admissionDTO;
        }

        return $admissionDTO->withAddedby(
            $this->getModel($admissionDTO->account, $admissionDTO->accountId)
        );
    }
}
