<?php

namespace App\Services;

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
        QuestionData $questionData
    ) : Question
    {
        $question = $this->createOrUpdateQuestion($questionData, 'create');

        $question = $this->attachQuestionToItem(
            question: $question,
            questionData: $questionData
        );

        $question = $this->addPossibleAnswers($question, $questionData);

        $this->checkPossibleAnswers($question);

        return $question;
    }

    private function checkPossibleAnswers(Question $question)
    {
        if ($question->doesntRequireOptionalAnswers()) return;

        if ($question->doesntHaveOptionalAnswers()) {
            $this->throwQuestionException(
                message: 'The question requires options from which choices will be made. But no option was given',
                data: $question
            );
        }
        
        if ($question->doesntHaveRequiredNumberOfOptionalAnswers()) {
            $this->throwQuestionException(
                message: 'The question with options requires at least two options. Add more options.',
                data: $question
            );
        }
    }

    public function updateQuestion(QuestionData $questionData)
    {
        $question = $this->createOrUpdateQuestion($questionData, 'update');
        
        $question = $this->removePossibleAnswers($question, $questionData);

        $question = $this->editPossibleAnswers($question, $questionData);

        $question = $this->addPossibleAnswers($question, $questionData);

        $this->checkPossibleAnswers($question);

        return $question;
    }

    private function attachQuestionToItem
    (
        Question $question,
        QuestionData $questionData
    )
    {
        $question->questionable()->associate($questionData->questionable);
        $question->save();

        return $question->refresh();
    }

    private function createOrUpdateQuestion
    (
        QuestionData $questionData,
        string $method
    ) : Question
    {
        $data = [
            'question' => $questionData->question,
            'state' => $questionData->state,
            'hint' => $questionData->hint,
            'position' => $questionData->position,
            'score_over' => $questionData->scoreOver,
            'answer_type' => $questionData->answerType,
            'published' => $questionData->published?->toDateTimeString(),
        ];

        $question = null;

        if ($method === 'create') {
            $question = $questionData->questionedby->questionsAdded()
                ->create($data);
        }
        
        if ($method === 'update') {
            $question = getYourEduModel('question',$questionData->questionId)
                ?->update($data);
        }
        
        if (is_null($question)) {
            $this->throwQuestionException(
                message: "failed to {$method} question.",
                data: $questionData
            );
        }

        return $question;
    }

    private function addPossibleAnswers
    (
        Question $question,
        QuestionData $questionData
    ) : Question
    {
        foreach ($questionData->possibleAnswers as $possibleAnswer) {
            $question->possibleAnswers()->create([
                'option' => $possibleAnswer->option,
            ]);
        }

        return $question->refresh();
    }
    
    private function editPossibleAnswers
    (
        Question $question,
        QuestionData $questionData
    ) : Question
    {
        foreach ($questionData->editedPossibleAnswers as $possibleAnswer) {
            $question->possibleAnswers()
                ->where('id',$possibleAnswer->possibleAnswerId)->first()
                ?->update([
                    'option' => $possibleAnswer->option,
                ]);
        }

        return $question->refresh();
    }

    private function removePossibleAnswers
    (
        Question $question,
        QuestionData $questionData
    ) : Question
    {
        foreach ($questionData->possibleAnswers as $possibleAnswer) {
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

    private function getModel($itemId) : Question
    {
        $question = getYourEduModel('question',$itemId);

        if (is_null($question)) {
            throw new AccountNotFoundException("question not found with id {$itemId}");
        }

        return $question;
    }

    public function deleteQuestion(QuestionData $questionData, $returnType = 'array')
    {
        $question = $this->getModel($questionData->questionId);

        $this->checkAuthorization($question, $questionData);

        if ($questionData->action === 'self') {
            return $this->removeQuestionForSelf(
                question: $question,
                questionData: $questionData
            );
        }

        $data = $this->removeQuestion($question);
        if ($returnType === 'array') {
            $data = $this->removeQuestionReturnedData($questionData);
        }

        return $data;
    }

    private function removeQuestionForSelf
    (
        Question $question,
        QuestionData $questionData
    ) : Question
    {
        $question->timestamps = false;
        if (is_null($question->user_deletes)) {
            $question->user_deletes = [$questionData->userId];
        } else {
            $question->user_deletes = Arr::prepend($question->user_deletes, $questionData->userId);
        }
        $question->save();
        return $question->load('images','videos','audios','files','flags','raisedby.profile');
    }

    private function checkAuthorization($question, $questionData)
    {
        if ($question->questionedby->user_id !== $questionData->userId) {
            $this->throwQuestionException(
                message: "you are not authorized to delete this question",
                data: $question
            );
        }
    }

    private function removeQuestion
    (
        Question $question
    ) : bool
    {
        $question->setTouchedRelations([]);
        $question->timestamps = false;
        FileService::deleteYourEduFiles($question); //throw error on delete fail
        
        $successCheck = $question->delete();
        if (!$successCheck) {
            $this->throwQuestionException(
                message: "failed to delete question",
                data: $question
            );
        }
        return $successCheck;
    }

    private function removeQuestionReturnedData($questionData)
    {
        return [
            'item' => 'question',
            'itemId' => $questionData->questionId
        ];
    }
}

?>