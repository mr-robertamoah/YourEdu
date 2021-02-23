<?php

namespace App\Services;

use App\DTOs\AssessmentData;
use App\Exceptions\AccountNotFoundException;
use App\Exceptions\AssessmentException;
use App\Http\Resources\AssessmentResource;
use App\Notifications\AssessmentNotification;
use App\User;
use App\YourEdu\Assessment;
use App\YourEdu\AssessmentSection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Notification;

class AssessmentService
{
    public function createAssessment
    (
        AssessmentData $assessmentData
    ): Assessment
    {
        $$assessmentData->addedby = $this->getModel(
            $assessmentData->account,
            $assessmentData->accountId
        );

        $assessment = $this->makeAssessment($assessmentData);

        $assessment = $this->addAssessmentSections($assessment,$assessmentData);

        $assessment = $this->attachAssessmentToItems($assessment,$assessmentData);

        $this->checkAssessmentSections($assessment);

        $this->notifyAttachedItemsAccounts(
            $assessment,
            "am assessment with name: {$assessment->name} has been added."
        );

        return $assessment;
    }

    private function notifyAttachedItemsAccounts
    (
        Assessment $assessment,
        string $message,
    ) : void
    {
        $userIds = $this->getItemsUserIds(
            assessment: $assessment,
        );

        $this->notifyUsers(
            users: User::whereIn('id',$userIds)->get(),
            message: $message,
            assessment: $assessment
        );
    }

    private function getItemsUserIds
    (
        Assessment $assessment, 
        bool $authority = false
    ) : array
    {
        $userIds = [];
        foreach ($assessment->allItems() as $item) {
            array_push($userIds,...$item->getAuthorizedUserIds($authority));
        }

        return array_unique($userIds);
    }

    private function notifyUsers
    (
        Collection $users,
        $message,
        Assessment $assessment = null
    )
    {
        Notification::send(
            $users,
            new AssessmentNotification(
                message: $message,
                assessmentResource: new AssessmentResource($assessment)
            )
        );
    }

    public function attachAssessmentToItems
    (
        Assessment $assessment,
        AssessmentData $assessmentData
    ) : Assessment
    {
        foreach ($assessmentData->questionables as $questionable) {
            $item = $this->getModel($questionable->item, $questionable->itemId);

            $item->assessments()->attach($assessment);
            $item->save();
        }
        
        return $assessment->refresh();
    }

    public function detachAssessmentFromItems
    (
        Assessment $assessment,
        AssessmentData $assessmentData
    ) : Assessment
    {
        foreach ($assessmentData->removedQuestionables as $questionable) {
            $item = $this->getModel($questionable->item, $questionable->itemId);

            $item->assessments()
                ->where('id',$assessment->id)?->detach($assessment);
            $item->save();
        }
        
        return $assessment->refresh();
    }

    public function addAssessmentSections
    (
        Assessment $assessment,
        AssessmentData $assessmentData,
        bool $checkIfTaken = false
    ) : Assessment
    {
        if ($checkIfTaken) {
            $this->ensureAssessmentNotTaken($assessment);
        }
        
        foreach ($assessmentData->sections as  $sectionData) {
            $assessmentSection = $this->createOrUpdateAssessmentSection(
                assessment: $assessment,
                assessmentSectionData: $sectionData,
                'create'
            );

            $this->addAssessmentQuestions(
                $assessmentData,
                $assessmentSection, 
                $sectionData
            );
        }
        
        return $assessment->refresh();
    }

    private function addAssessmentQuestions
    (
        AssessmentData $assessmentData,
        AssessmentSection $assessmentSection,
        AssessmentSectionData $assessmentSectionData,
    )
    {
        foreach ($assessmentSectionData->questions as $questionData) {
            
            $questionData = $this->updateQuestionData(
                questionData: $questionData,
                assessmentData: $assessmentData,
                assessmentSection: $assessmentSection
            );

            (new QuestionService)->createQuestion($questionData);
        }
    }

    private function editAssessmentQuestions
    (
        AssessmentData $assessmentData,
        AssessmentSection $assessmentSection,
        AssessmentSectionData $assessmentSectionData,
    )
    {
        foreach ($assessmentSectionData->editedQuestions as $questionData) {
            
            $questionData = $this->updateQuestionData(
                questionData: $questionData,
                assessmentData: $assessmentData,
                assessmentSection: $assessmentSection
            );

            (new QuestionService)->updateQuestion($questionData);
        }
    }

    private function removeAssessmentQuestions
    (
        AssessmentData $assessmentData,
        AssessmentSection $assessmentSection,
        AssessmentSectionData $assessmentSectionData,
    )
    {
        foreach ($assessmentSectionData->questions as $questionData) {

            $questionData = $this->updateQuestionData(
                questionData: $questionData,
                assessmentData: $assessmentData,
                assessmentSection: $assessmentSection
            );

            (new QuestionService)->deleteQuestion($questionData, 'bool');
        }
    }

    private function updateQuestionData
    (
        QuestionData $questionData,
        AssessmentSection $assessmentSection,
        AssessmentData $assessmentData,
    ) : QuestionData
    {
        $questionData->questionable = $assessmentSection;
        $questionData->questionedby = $assessmentData->addedby;
        $questionData->state = null;

        return $questionData;
    }

    private function makeAssessment
    (
        AssessmentData $assessmentData,
    ) : Assessment
    {
        return $this->createOrUpdateAssessment($assessmentData, 'create');
    }

    private function createOrUpdateAssessmentSection
    (
        Assessment $assessment,
        AssessmentSectionData $assessmentSectionData,
        string $method
    ) : AssessmentSection
    {
        $data = [
            'name' => $assessmentSectionData->name,
            'instruction' => $assessmentSectionData->instruction,
            'position' => $assessmentSectionData->position,
            'random' => $assessmentSectionData->random,
            'max_questions' => $assessmentSectionData->maxQuestions,
            'auto_mark' => $assessmentSectionData->autoMark,
            'answer_type' => $assessmentSectionData->answerType,
        ];

        $assessmentSection = null;

        if ($method === 'create') {
            $assessmentSection = $assessment->assessmentSections()
                ->$method($data); 
        }
        
        if ($method === 'update') {
            $assessmentSection = $assessment->assessmentSections()
                ->where('id',$assessmentSectionData->sectionId)
                ->first()?->$method($data); 
        }

        if (is_null($assessmentSection)) {
            $this->throwAssessmentException(
                message: "failed to {$method} assessment section",
                data: $assessmentSectionData
            );
        }

        return $assessmentSection;
    }

    private function createOrUpdateAssessment
    (
        AssessmentData $assessmentData,
        string $method
    ) : Assessment
    {
        $assessment = $assessmentData->addedby->addedAssessments()->$method([
            'name' => $assessmentData->name,
            'description' => $assessmentData->description,
            'duration' => $assessmentData->duration,
            'total_mark' => $assessmentData->totalMark,
            'due_date' => $assessmentData->dueDate->toDateString(),
            'publish_date' => $assessmentData->publishDate->toDateString(),
        ]);

        if (is_null($assessment)) {
            $this->throwAssessmentException(
                message: "failed to {$method} assessment.",
                data: $assessmentData
            );
        }

        return $assessment;
    }

    private function throwAssessmentException
    (
        $message,
        $data = null
    )
    {
        throw new AssessmentException(
            message: $message, data: $data
        );
    }

    private function getModel($account, $accountId)
    {
        $mainAccount = getYourEduModel($account,$accountId);
        if (is_null($mainAccount)) {
            throw new AccountNotFoundException("{$account} with id {$accountId} was not found.");
        }

        return $mainAccount;
    }

    public function updateAssessment
    (
        AssessmentData $assessmentData
    ) : Assessment
    {

        $assessment = $this->getAssessmentWithId($assessmentData);

        $this->checkAuthorization($assessment, $assessmentData);
        
        $assessment = $this->editAssessment($assessment, $assessmentData);

        $assessment = $this->editAssessmentSections(
            $assessment, $assessmentData
        );

        $assessment = $this->removeAssessmentSections(
            $assessment, $assessmentData
        );

        $assessment = $this->addAssessmentSections(
            $assessment, $assessmentData, true
        );

        $this->checkAssessmentSections($assessment);
        
        $assessment = $this->attachAssessmentToItems($assessment,$assessmentData);

        $assessment = $this->detachAssessmentFromItems($assessment,$assessmentData);

        $this->notifyAttachedItemsAuthority($assessment);

        return $assessment;
    }

    private function checkAssessmentSections(Assessment $assessment)
    {
        if ($assessment->doesntHaveAssessmentSections()) {
            $this->throwAssessmentException(
                message: "an assessment requires at least one assessment section.",
                data: $assessment
            );
        }
    }

    private function ensureAssessmentNotTaken
    (
        Assessment $assessment,
    ) : void
    {
        if ($assessment->hasBeenTaken()) {
            $this->throwAssessmentException(
                message: "this assessment has already been taken and cannot have sections or questions changed.",
                data: $assessment
            );
        }
        
    }

    private function editAssessment
    (
        Assessment $assessment,
        AssessmentData $assessmentData
    ) : Assessment
    {
        return $this->insertAssessmentData($assessmentData, 'update');
    }

    private function editAssessmentSections
    (
        Assessment $assessment,
        AssessmentData $assessmentData
    ) : Assessment
    {
        if (count($assessmentData->editedSections) > 0 ) {
            $this->ensureAssessmentNotTaken($assessment);
        }
        
        foreach ($assessmentData->editedSections as $sectionData) {
            $assessmentSection = $this->createOrUpdateAssessmentSection(
                assessment: $assessment,
                assessmentSectionData: $sectionData,
                method: 'update'
            );

            $this->editAssessmentQuestions(
                assessmentData: $assessmentData,
                assessmentSection: $assessmentSection,
                assessmentSectionData: $sectionData
            );

            $this->removeAssessmentQuestions(
                assessmentData: $assessmentData,
                assessmentSection: $assessmentSection,
                assessmentSectionData: $sectionData
            );

            $this->addAssessmentQuestions(
                assessmentData: $assessmentData,
                assessmentSectionData: $sectionData,
                assessmentSection: $assessmentSection
            );
        }

        return $assessment->refresh();
    }

    private function removeAssessmentSections
    (
        Assessment $assessment,
        AssessmentData $assessmentData
    ) : Assessment
    {
        
        
        $this->ensureAssessmentNotTaken($assessment);
        return $assessment->refresh();
    }

    public function deleteAssessment
    (
        AssessmentData $assessmentData
    ) 
    {
        $assessmentData->addedby = $this->getModel(
            $assessmentData->account, 
            $assessmentData->accountId
        );
        
        $assessment = $this->getAssessmentWithId($assessmentData);

        $this->checkAuthorization($assessment, $assessmentData);

        $this->removeAssessment($assessment);

        $this->notifyAttachedItemsAuthority($assessment);

        return $assessment;
    }

    private function notifyAttachedItemsAuthority
    (
        Assessment $assessment,
        string $message,
    )
    {
        $userIds = $this->getItemsUserIds(
            assessment: $assessment,
            authority: true
        );

        $this->notifyUsers(
            users: User::whereIn('id',$userIds)->get(),
            message: $message,
            assessment: $assessment
        );
    }

    private function checkAuthorization($assessment, $assessmentData)
    {
        if ($assessmentData->userId !== $assessment->addedby?->user_id &&
            $assessmentData->userId !== $assessment->addedby?->owner_id) {
            $this->throwAssessmentException(
                message: "you are not authorized to update this assessment with id {$collaboration->id}"
            );
        }
        return true;
    }

    private function getAssessmentWithId(AssessmentData $assessmentData)
    {
        return $this->getModel('assessment', $assessmentData->assessmentId);
    }
    
    private function removeAssessment(Assessment $assessment) : bool
    {
        return $assessment->delete();
    }