<?php

namespace App\Services;

use App\DTOs\QuestionDTO;
use App\Exceptions\AccountNotFoundException;
use App\Exceptions\QuestionException;
use App\YourEdu\Question;
use Carbon\Carbon;
use \Debugbar;
use Illuminate\Support\Arr;

class QuestionService
{
    public function createQuestion
    (
        QuestionDTO $questionDTO
    ) : Question
    {
        $question = $this->createOrUpdateQuestion($questionDTO, 'create');

        $questionDTO = $questionDTO->withQuestion($question);

        $question = $this->attachQuestionToItem(
            question: $question,
            questionDTO: $questionDTO
        );

        $question = $this->addPossibleAnswers($question, $questionDTO);

        $this->checkPossibleAnswers($question, $questionDTO);

        $question = $this->addQuestionFiles($question, $questionDTO);

        return $question;
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
        if ($question->doesntRequireOptionalAnswers()) return;

        if ($question->doesntHaveOptionalAnswers()) {
            $this->throwQuestionException(
                message: 'The question requires options from which choices will be made. But no option was given',
                data: $questionDTO
            );
        }
        
        if ($question->doesntHaveRequiredNumberOfOptionalAnswers()) {
            $this->throwQuestionException(
                message: 'The question with options requires at least two options. Add more options.',
                data: $questionDTO
            );
        }
    }

    public function updateQuestion(QuestionDTO $questionDTO)
    {
        $question = $this->createOrUpdateQuestion($questionDTO, 'update');
        
        $questionDTO = $questionDTO->withQuestion($question);

        $question = $this->removePossibleAnswers($question, $questionDTO);

        $question = $this->editPossibleAnswers($question, $questionDTO);

        $question = $this->addPossibleAnswers($question, $questionDTO);

        $this->checkPossibleAnswers($question, $questionDTO);

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

    private function createOrUpdateQuestion
    (
        QuestionDTO $questionDTO,
        string $method
    ) : Question
    {
        $data = [
            'body' => $questionDTO->body,
            'state' => $questionDTO->state,
            'hint' => $questionDTO->hint,
            'position' => $questionDTO->position,
            'score_over' => $questionDTO->scoreOver,
            'answer_type' => $questionDTO->answerType,
            'published_at' => $questionDTO->publishedAt?->toDateTimeString(),
        ];

        $question = null;

        if ($method === 'create') {
            $question = $questionDTO->addedby->questionsAdded()
                ->create($data);
        }
        
        if ($method === 'update') {
            $question = getYourEduModel('question',$questionDTO->questionId);
                
            $question?->update($data);
        }
        
        if (is_null($question)) {
            $this->throwQuestionException(
                message: "failed to {$method} question.",
                data: $questionDTO
            );
        }

        return $question->refresh();
    }

    private function addPossibleAnswers
    (
        Question $question,
        QuestionDTO $questionDTO
    ) : Question
    {
        foreach ($questionDTO->possibleAnswers as $possibleAnswerDTO) {
            $possibbleAnswer = $question->possibleAnswers()->create([
                'option' => $possibleAnswerDTO->option,
                'position' => $possibleAnswerDTO->position,
            ]);
        }

        $question = $this->setCorrectPossibleAnswers($question, $questionDTO);

        return $question->refresh();
    }

    private function setCorrectPossibleAnswers
    (
        Question $question,
        QuestionDTO $questionDTO,
        string $type = 'id'
    ) : Question
    {
        if ($question->doesntHaveOptionalAnswers()) return $question;

        if ($type === 'id') {
            $correctAnswerIds = $this->getCorrectPossibleAnswerIdsById(
                $question, $questionDTO
            );
        } 
        
        if ($question->isArrangeFlowAnswerType()) {
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

        foreach ($questionDTO->correctPossibleAnswers as $possibleAnswerDTO) {
            if ($question->isTrueOrFalseOptionAnswerType()) {
                $correctAnswerIds = [$possibleAnswerDTO->id];
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

        foreach ($questionDTO->correctPossibleAnswers as $possibleAnswerDTO) {
            $id = $question->getPossibleAnswerId(
                $possibleAnswerDTO->option
            );
            if ($question->isTrueOrFalseOptionAnswerType()) {
                $correctAnswerIds = [$id];
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

    private function getModel(QuestionDTO $questionDTO) : Question
    {
        if ($questionDTO->question) {
            return $questionDTO->question;
        }

        $question = getYourEduModel('question',$questionDTO->questionId);

        if (is_null($question)) {
            throw new AccountNotFoundException("question not found with id {$itemId}");
        }

        return $question;
    }

    public function deleteQuestion
    (
        QuestionDTO $questionDTO, 
        $check = false
    )
    {
        $question = $this->getModel($questionDTO);

        if ($check) {
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
        $question = $this->getModel($questionDTO);

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
        FileService::deleteYourEduItemFiles($question);
        
        $successCheck = $question->delete();
        if (!$successCheck) {
            $this->throwQuestionException(
                message: "failed to delete question",
                data: $questionDTO
            );
        }

        return $successCheck;
    }
}

?>