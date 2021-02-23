<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class AnswerService
{
    public function createAnswer($request, $account, $answerable, $chat = false)
    {
        $answerPossibleAnswerId = null;
        if ($request->has('possible_answer_id') && 
            $request->possible_answer_id !== ''  && 
            !is_null($request->possible_answer_id)) {
            $answerPossibleAnswerId = (int) $request->possible_answer_id;
        } 

        $answer = $account->answers()->create([
            'answer' => $request->answer,
            'possible_answer_id' => $answerPossibleAnswerId,
        ]);

        $dontRollBack = $this->answerAssociate($answer, $answerable);

        if (!$dontRollBack) {
            return null;
        }

        FileService::createAndAttachFiles(
            account: $account,
            file: $request->file('file'),
            item: $answer
        );

        if (!$chat) {
            $account->point->value += 1;
            $account->point->save();
        }
        
        return $answer;
    }
    
    private function answerAssociate($answer, $item)
    {
        if ($answer && $item) {
            $answer->answerable()->associate($item);
            $answer->save();
        } else {
            return false;
        }
        return true;
    }
}

?>