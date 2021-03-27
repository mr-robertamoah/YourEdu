<?php

namespace App\Services;

use App\DTOs\AssessmentSectionDTO;
use App\DTOs\QuestionDTO;
use App\Exceptions\AccountNotFoundException;
use App\Exceptions\AssessmentSectionException;
use App\YourEdu\AssessmentSection;

class AssessmentSectionService
{
    public function createAssessmentSection
    (
        AssessmentSectionDTO $assessmentSectionDTO
    ) : AssessmentSection
    {
        $this->checkRequiredData(
            assessmentSectionDTO: $assessmentSectionDTO
        );

        $assessmentSection = $this->createOrUpdateAssessmentSection(
            assessmentSectionDTO: $assessmentSectionDTO,
            method: 'create'
        );

        $assessmentSection = $this->addAssessmentSectionQuestions(
            $assessmentSection, 
            $assessmentSectionDTO
        );

        return $assessmentSection;
    }

    public function updateAssessmentSection
    (
        AssessmentSectionDTO $assessmentSectionDTO
    ) : AssessmentSection
    {
        $assessmentSection = $this->createOrUpdateAssessmentSection(
            assessmentSectionDTO: $assessmentSectionDTO,
            method: 'update'
        );

        $this->checkRequiredData(
            assessmentSectionDTO: $assessmentSectionDTO,
            assessmentSection: $assessmentSection
        );

        $this->editAssessmentSectionQuestions(
            assessmentSection: $assessmentSection,
            assessmentSectionDTO: $assessmentSectionDTO
        );

        $this->removeAssessmentSectionQuestions(
            assessmentSection: $assessmentSection,
            assessmentSectionDTO: $assessmentSectionDTO
        );

        $this->addAssessmentSectionQuestions(
            assessmentSectionDTO: $assessmentSectionDTO,
            assessmentSection: $assessmentSection
        );

        return $assessmentSection;
    }

    public function deleteAssessmentSection
    (
        AssessmentSectionDTO $assessmentSectionDTO
    ) : bool
    {
        $assessmentSection = $this->getAssessmentSectionModel($assessmentSectionDTO);

        $assessmentSection = $this->deleteAssessmentSectionQuestions($assessmentSection);

        return $this->removeAssessmentSection($assessmentSection, $assessmentSectionDTO);
    }

    private function deleteAssessmentSectionQuestions
    (
        AssessmentSection $assessmentSection
    )
    {
        $assessmentSection->questions->each(function($question) {

            FileService::deleteYourEduItemFiles($question);

            $question->delete();
        });

        return $assessmentSection->refresh();
    }

    private function removeAssessmentSection
    (
        AssessmentSection $assessmentSection,
        AssessmentSectionDTO $assessmentSectionDTO
    )
    {
        $deletionStatus = $assessmentSection->delete();

        if (!$deletionStatus) {
            $this->throwAssessmentSectionException(
                message: "",
                data: $assessmentSectionDTO
            );
        }
        return $deletionStatus;
    }

    private function addAssessmentSectionQuestions
    (
        AssessmentSection $assessmentSection,
        AssessmentSectionDTO $assessmentSectionDTO,
    )
    {
        foreach ($assessmentSectionDTO->questions as $questionDTO) {
            
            $questionDTO = $this->updateQuestionDTO(
                questionDTO: $questionDTO,
                assessmentSection: $assessmentSection,
                assessmentSectionDTO: $assessmentSectionDTO
            );

            (new QuestionService)->createQuestion($questionDTO);
        }

        return $assessmentSection->refresh();
    }

    private function editAssessmentSectionQuestions
    (
        AssessmentSection $assessmentSection,
        AssessmentSectionDTO $assessmentSectionDTO,
    )
    {
        foreach ($assessmentSectionDTO->editedQuestions as $questionDTO) {
            
            $questionDTO = $this->updateQuestionDTO(
                questionDTO: $questionDTO,
                assessmentSection: $assessmentSection,
                assessmentSectionDTO: $assessmentSectionDTO
            );

            (new QuestionService)->updateQuestion($questionDTO);
        }
    }

    private function removeAssessmentSectionQuestions
    (
        AssessmentSection $assessmentSection,
        AssessmentSectionDTO $assessmentSectionDTO,
    )
    {
        foreach ($assessmentSectionDTO->removedQuestions as $questionDTO) {

            $questionDTO = $this->updateQuestionDTO(
                questionDTO: $questionDTO,
                assessmentSection: $assessmentSection,
                assessmentSectionDTO: $assessmentSectionDTO
            );

            (new QuestionService)->deleteQuestion($questionDTO);
        }
    }

    private function updateQuestionDTO
    (
        QuestionDTO $questionDTO,
        AssessmentSectionDTO $assessmentSectionDTO,
        AssessmentSection $assessmentSection,
    ) : QuestionDTO
    {
        $questionDTO->questionable = $assessmentSection;
        $questionDTO->addedby = $assessmentSectionDTO->addedby;
        $questionDTO->autoMark = $assessmentSection->auto_mark;
        $questionDTO->state = null;

        return $questionDTO;
    }

    private function createOrUpdateAssessmentSection
    (
        AssessmentSectionDTO $assessmentSectionDTO,
        string $method
    ) : AssessmentSection
    {
        $data = [
            'name' => $assessmentSectionDTO->name,
            'instruction' => $assessmentSectionDTO->instruction,
            'position' => $assessmentSectionDTO->position,
            'random' => $assessmentSectionDTO->random,
            'max_questions' => $assessmentSectionDTO->maxQuestions,
            'auto_mark' => $assessmentSectionDTO->autoMark,
            'answer_type' => AssessmentService::getAnswerType($assessmentSectionDTO->answerType),
        ];

        $assessmentSection = null;

        if ($method === 'create') {
            $assessmentSection = $assessmentSectionDTO
                ->assessment->assessmentSections()
                ->$method($data); 
        }
        
        if ($method === 'update') {
            $assessmentSection = $this->getAssessmentSectionModel(
                $assessmentSectionDTO
            );
                
            $assessmentSection?->$method($data); 
        }

        if (is_null($assessmentSection)) {
            $this->throwAssessmentException(
                message: "failed to {$method} assessment section",
                data: $assessmentSectionDTO
            );
        }

        return $assessmentSection->refresh();
    }

    private function getAssessmentSectionModel
    (
        AssessmentSectionDTO $assessmentSectionDTO
    )
    {
        if ($assessmentSectionDTO->assessmentSection) {
            return $assessmentSectionDTO->assessmentSection;
        }

        $assessmentSection = getYourEduModel('assessmentSection', $assessmentSectionDTO->assessmentSectionId);
        if (is_null($assessmentSection)) {
            throw new AccountNotFoundException(
                "assessment section with id {$assessmentSectionDTO->assessmentSectionId} not found."
            );
        }

        return $assessmentSection;
    }

    private function checkRequiredData
    (
        AssessmentSection $assessmentSection = null,
        AssessmentSectionDTO $assessmentSectionDTO
    )
    {
        if (count($assessmentSectionDTO->questions)) {
            return;
        }

        if ($assessmentSection?->notRemovingAllQuestions($assessmentSectionDTO->removedQuestions)) {
            return;
        }

        $this->throwAssessmentSectionException(
            message: "am assessment section requires at least one question",
            data: $assessmentSectionDTO
        );
    }

    private function throwAssessmentSectionException
    (
        string $message,
        $data = null
    )
    {
        throw new AssessmentSectionException(
            message: $message,
            data: $data
        );
    }
}
