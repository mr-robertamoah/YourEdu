<?php

namespace App\Services;

use App\DTOs\AssessmentSectionDTO;
use App\DTOs\QuestionDTO;
use App\Exceptions\AccountNotFoundException;
use App\Exceptions\AssessmentSectionException;
use App\Traits\ServiceTrait;
use App\YourEdu\AssessmentSection;

class AssessmentSectionService
{
    use ServiceTrait;

    public function createAssessmentSection
    (
        AssessmentSectionDTO $assessmentSectionDTO
    ) : AssessmentSection
    {
        $assessmentSectionDTO = $assessmentSectionDTO->addData(method: 'create');

        $this->checkRequiredData(
            assessmentSectionDTO: $assessmentSectionDTO
        );

        $assessmentSection = $this->createOrUpdateAssessmentSection(
            assessmentSectionDTO: $assessmentSectionDTO,
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
        $assessmentSectionDTO = $assessmentSectionDTO->addData(method: 'update');
        
        $assessmentSection = $this->createOrUpdateAssessmentSection(
            assessmentSectionDTO: $assessmentSectionDTO
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
                questionDTO: $questionDTO->addData(mustHaveScoreOver: true),
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

        if ($assessmentSectionDTO->method === 'create') {
            $assessmentSection = $assessmentSectionDTO
                ->assessment->assessmentSections()
                ->create($data); 
        }
        
        if ($assessmentSectionDTO->method === 'update') {
            $assessmentSection = $this->getAssessmentSectionModel(
                $assessmentSectionDTO
            );
                
            $assessmentSection?->update($data); 
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

        return $this->getModel('assessmentSection', $assessmentSectionDTO->assessmentSectionId);
    }

    private function checkRequiredData
    (
        AssessmentSection $assessmentSection = null,
        AssessmentSectionDTO $assessmentSectionDTO
    )
    {
        $this->ensureAppropriateRandomAndMaxQuestionsCombination($assessmentSectionDTO);

        if ($assessmentSectionDTO->method === 'create' && 
            $assessmentSectionDTO->hasEnoughQuestions()) {
            return;
        }

        if ($assessmentSectionDTO->method === 'update' && 
            $assessmentSection?->willHaveEnoughQuestions($assessmentSectionDTO)) {
            return;
        }

        $this->throwAssessmentSectionException(
            message: $assessmentSectionDTO->maxQuestions ? 
                "sorry 😞, to {$assessmentSectionDTO->method} an assessment section, ensure it has at least the max number of questions" : 
                "sorry 😞, to {$assessmentSectionDTO->method} an assessment section, ensure it has at least one question",
            data: $assessmentSectionDTO
        );
    }

    private function ensureAppropriateRandomAndMaxQuestionsCombination($assessmentSectionDTO)
    {
        if ($assessmentSectionDTO->hasAppropriateRandomAndMaxQuestionsData()) {
            return;
        }

        $this->throwAssessmentSectionException(
            message: "sorry 😞, please fill the max questions field, with a number greater than 0, since it is required for a randomized assessment section.",
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
