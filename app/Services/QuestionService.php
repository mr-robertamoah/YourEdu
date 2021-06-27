<?php

namespace App\Services;

use App\DTOs\QuestionDTO;
use App\Exceptions\QuestionException;
use App\Traits\ServiceTrait;
use App\YourEdu\Question;
use \Debugbar;
use Illuminate\Support\Arr;

class QuestionService
{
    use ServiceTrait;

    public function createQuestion(QuestionDTO $questionDTO) : Question
    {
        $this->checkScoreOver($questionDTO);

        $question = $this->createOrUpdateQuestion($questionDTO->addData(method: 'create'));

        $questionDTO = $questionDTO->withQuestion($question);

        $question = $this->attachQuestionToItem(
            question: $question,
            questionDTO: $questionDTO
        );

        $question = $this->addPossibleAnswers($question, $questionDTO);

        $this->checkPossibleAnswers($question, $questionDTO);

        $question = $this->setCorrectPossibleAnswers($question, $questionDTO);

        $question = $this->addQuestionFiles($question, $questionDTO);

        return $question;
    }

    private function checkScoreOver($questionDTO)
    {
        if (! $questionDTO->mustHaveScoreOver) {
            return;
        }

        if (is_not_null($questionDTO->scoreOver) && is_numeric($questionDTO->scoreOver)) {
            return;
        }

        $this->throwQuestionException(
            message: "sorry 😞, please provide a score over field for the question: {$questionDTO->body}",
            data: $questionDTO
        );
    }

    private function addQuestionFiles
    (
        Question $question,
        QuestionDTO $questionDTO,
    )
    {
        foreach ($questionDTO->files as $file) {

            FileService::createAndAttachFiles(
                account: $questionDTO->addedby, 
                file: $file,
                item: $question
            );
        }

        return $question->refresh();
    }

    private function removeQuestionFiles
    (
        Question $question,
        QuestionDTO $questionDTO,
    )
    {
        foreach ($questionDTO->removedFiles as $file) {
            FileService::deleteAndUnattachFiles(
                item: $question,
                file: $file
            );
        }

        return $question->refresh();
    }

    private function deleteQuestionFiles
    (
        Question $question,
    )
    {
        FileService::deleteYourEduItemFiles(
            item: $question,
        );

        return $question->refresh();
    }

    private function checkPossibleAnswers
    (
        Question $question,
        QuestionDTO $questionDTO
    )
    {
        if ($question->doesntRequirePossibleAnswers()) return;

        if ($question->doesntHavePossibleAnswers()) {
            $this->throwQuestionException(
                message: 'The question requires options from which choices will be made. But no option was given',
                data: $questionDTO
            );
        }
        
        if ($question->doesntHaveRequiredNumberOfPossibleAnswers()) {
            $this->throwQuestionException(
                message: 'The question with options requires at least two options. Add more options.',
                data: $questionDTO
            );
        }
    }

    public function updateQuestion(QuestionDTO $questionDTO)
    {
        $question = $this->createOrUpdateQuestion($questionDTO->addData(method:'update'));
        
        $questionDTO = $questionDTO->withQuestion($question);

        $question = $this->removePossibleAnswers($question, $questionDTO);

        $question = $this->editPossibleAnswers($question, $questionDTO);

        $question = $this->addPossibleAnswers($question, $questionDTO);

        $this->checkPossibleAnswers($question, $questionDTO);

        $question = $this->setCorrectPossibleAnswers($question, $questionDTO);

        $question = $this->addQuestionFiles($question, $questionDTO);

        $question = $this->removeQuestionFiles($question, $questionDTO);

        return $question;
    }

    private function attachQuestionToItem
    (
        Question $question,
        QuestionDTO $questionDTO
    ) : Question
    {
        if (!$questionDTO->questionable) return $question;

        $question->questionable()->associate($questionDTO->questionable);
        $question->save();

        return $question->refresh();
    }

    private function createOrUpdateQuestion(QuestionDTO $questionDTO) : Question
    {
        $data = [];

        if ($questionDTO->body) {
            $data['body'] = $questionDTO->body;
        }
        
        if ($questionDTO->state) {
            $data['state'] = $questionDTO->state;
        }
        
        if ($questionDTO->hint) {
            $data['hint'] = $questionDTO->hint;
        }
        
        if ($questionDTO->position) {
            $data['position'] = $questionDTO->position;
        }
        if ($questionDTO->scoreOver) {
            $data['score_over'] = $questionDTO->scoreOver;
        }
        if ($questionDTO->answerType) {
            $data['answer_type'] = AssessmentService::getAnswerType($questionDTO->answerType);
        }
        if ($questionDTO->publishedAt) {
            $data['published_at'] = $questionDTO->publishedAt?->toDateTimeString();
        }

        $question = null;

        if ($questionDTO->method === 'create') {
            $question = $questionDTO->addedby->questionsAdded()
                ->create($data);
        }
        
        if ($questionDTO->method === 'update') {
            $question = getYourEduModel('question',$questionDTO->questionId);
                
            $question?->update($data);
        }
        
        if (is_not_null($question)) {
            return $question->refresh();
        }
        
        $this->throwQuestionException(
            message: "failed to {$question->method} question.",
            data: $questionDTO
        );
    }

    private function addPossibleAnswers
    (
        Question $question,
        QuestionDTO $questionDTO
    ) : Question
    {
        foreach ($questionDTO->possibleAnswers as $possibleAnswerDTO) {
            $question->possibleAnswers()->create([
                'option' => $possibleAnswerDTO->option,
                'position' => $possibleAnswerDTO->position,
            ]);
        }

        return $question->refresh();
    }

    private function setCorrectPossibleAnswers
    (
        Question $question,
        QuestionDTO $questionDTO,
    ) : Question
    {
        if ($question->doesntHavePossibleAnswers()) {
            return $question;
        }
        
        if (! count($questionDTO->correctPossibleAnswers)) {
            return $question;
        }

        $correctAnswerIds = $this->getCorrectPossibleAnswerIdsById(
            $question, $questionDTO
        );
        
        if (! count($correctAnswerIds)) {
            $correctAnswerIds = $this->getCorrectPossibleAnswerIdsByOption(
                $question, $questionDTO
            );
        }

        $question->correct_possible_answers = $correctAnswerIds;
        $question->save();

        return $question;
    }

    private function getCorrectPossibleAnswerIdsById
    (
        Question $question,
        QuestionDTO $questionDTO
    )
    {
        $correctAnswerIds = [];

        if (! property_exists($questionDTO->correctPossibleAnswers[0], 'id')) {
            return $correctAnswerIds;
        }

        foreach ($questionDTO->correctPossibleAnswers as $possibleAnswerDTO) {
            if ($question->isTrueOrFalseOptionAnswerType()) {
                $correctAnswerIds = [$possibleAnswerDTO->id];
                continue;
            } 

            if ($question->isArrangeFlowAnswerType()) {
                $correctAnswerIds[] = $possibleAnswerDTO->id;
            }   
        }

        return $correctAnswerIds;
    }

    private function getCorrectPossibleAnswerIdsByOption
    (
        Question $question,
        QuestionDTO $questionDTO
    ) 
    {
        $correctAnswerIds = [];

        if (! property_exists($questionDTO->correctPossibleAnswers[0], 'option')) {
            return $correctAnswerIds;
        }

        foreach ($questionDTO->correctPossibleAnswers as $possibleAnswerDTO) {
            $id = $question->getPossibleAnswerId(
                $possibleAnswerDTO->option
            );

            if ($question->isTrueOrFalseOptionAnswerType()) {
                $correctAnswerIds = [$id];
                continue;
            } 

            if ($question->isArrangeFlowAnswerType()) {
                $correctAnswerIds[] = $id;
            }                
        }

        return $correctAnswerIds;
    }
    
    private function editPossibleAnswers
    (
        Question $question,
        QuestionDTO $questionDTO
    ) : Question
    {
        foreach ($questionDTO->editedPossibleAnswers as $possibleAnswer) {
            $question->possibleAnswers()
                ->where('id',$possibleAnswer->possibleAnswerId)->first()
                ?->update([
                    'option' => $possibleAnswer->option,
                    'position' => $possibleAnswer->position,
                ]);
        }

        return $question->refresh();
    }

    private function removePossibleAnswers
    (
        Question $question,
        QuestionDTO $questionDTO
    ) : Question
    {
        foreach ($questionDTO->removedPossibleAnswers as $possibleAnswer) {
            $question->possibleAnswers()
                ->where('id',$possibleAnswer->possibleAnswerId)->first()
                ?->delete();
        }

        return $question->refresh();
    }

    private function throwQuestionException
    (
        string $message, $data = null
    )
    {
        throw new QuestionException(
            message: $message,
            data: $data
        );
    }

    private function getQuestionModel(QuestionDTO $questionDTO) : Question
    {
        if ($questionDTO->question) {
            return $questionDTO->question;
        }

        return $this->getModel('question', $questionDTO->questionId);
    }

    public function deleteQuestion
    (
        QuestionDTO $questionDTO,
    )
    {
        $question = $this->getQuestionModel($questionDTO);

        if ($questionDTO->checkAuthorization) {
            $this->checkAuthorization($question, $questionDTO);
        }

        $this->deleteQuestionFiles($question);
        
        return $this->removeQuestion($question, $questionDTO);
    }

    public function setUserDeletes
    (
        QuestionDTO $questionDTO
    ) : Question
    {
        $question = $this->getQuestionModel($questionDTO);

        $question->setTouchedRelations([]);
        $question->timestamps = false;

        if (is_null($question->user_deletes)) {
            $question->user_deletes = [$questionDTO->userId];
        } else {
            $question->user_deletes = Arr::prepend($question->user_deletes, $questionDTO->userId);
        }

        $question->save();

        return $question->load('images','videos','audios','files','flags','raisedby.profile');
    }

    private function checkAuthorization($question, $questionDTO)
    {
        if ($question->addedby->user_id !== $questionDTO->userId) {
            $this->throwQuestionException(
                message: "you are not authorized to delete this question",
                data: $questionDTO
            );
        }
    }

    private function removeQuestion
    (
        Question $question,
        QuestionDTO $questionDTO
    ) : bool
    {
        $successCheck = $question->delete();

        if (! $successCheck) {
            $this->throwQuestionException(
                message: "failed to delete question",
                data: $questionDTO
            );
        }

        return $successCheck;
    }

    public function answerQuestion(QuestionDTO $questionDTO)
    {
        
    }
}

?>