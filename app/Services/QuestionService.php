<?php

namespace App\Services;

use App\Exceptions\AccountNotFoundException;
use App\Exceptions\QuestionException;
use Carbon\Carbon;
use \Debugbar;
use Illuminate\Support\Arr;

class QuestionService
{
    public function createQuestion($request, $questionable, $account,$type)
    {
        $scoreOver = (int)$request->score;
        if ((int)$request->score > 100) {
            $scoreOver = 100;
        } else if (is_null($request->score) || (int)$request->score < 5) {
            $scoreOver = 5;
        }

        $published = null;
        if ($request->has('published') && !is_null($request->published)) {
            $published = Carbon::parse($request->published)->toDateTimeString();
        } 

        $state = null;
        if ($type === 'post') {
            $state = 'PENDING';
        } else if ($type === 'conversation') {
            $state = 'SENT';
        }
        $question = $account->questionsAdded()->create([
            'question' => $request->question,
            'state' => $state,
            'score_over' => $scoreOver,
            'published' => $published,
        ]);
        $question->questionable()->associate($questionable);
        $question->save();

        if ($request->has('possibleAnswers') && 
            !is_null($request->possibleAnswers)) {
            $possibleAnswers = json_decode($request->possibleAnswers);
            foreach ($possibleAnswers as $possibleAnswer) {
                $question->possibleAnswers()->create([
                    'option' => $possibleAnswer,
                ]);
            }
        }

        return $question;
    }

    public function deleteQuestion($userId, $questionId, $action)
    {
        $question = getAccountObject('question', $questionId);

        if (is_null($question)) {
            throw new AccountNotFoundException("question not found with id {$questionId}");
        }

        if ($action === 'self') {
            $question->timestamps = false;
            if (is_null($question->user_deletes)) {
                $question->user_deletes = [$userId];
            } else {
                $question->user_deletes = Arr::prepend($question->user_deletes, $userId);
            }
            $question->save();
            return $question->load('images','videos','audios','files','flags','raisedby.profile');
        } else if ($action === 'delete') {
            if ($question->questionedby->user_id !== $userId) {
                throw new QuestionException("you are not authorized to delete this question");
            }

            deleteYourEduFiles($question);
            $question->setTouchedRelations([]);
            $question->timestamps = false;
            $question->delete();
            return [
                'item' => 'question',
                'itemId' => $questionId
            ];
        }
    }
}

?>