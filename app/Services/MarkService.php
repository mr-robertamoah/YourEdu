<?php

namespace App\Services;

class MarkService
{
    public function createMark($request, $account, $markable, $chat = false)
    {
        $mark = $account->marks()->create([
            'user_id' => auth()->id(),
            'remark' => $request->remark,
            'state' => strtoupper($request->state),
            'score' => (int)$request->score,
            'score_over' => (int)$request->score_over,
        ]);
        if (!$chat) {
            $account->point->value += 1;
            $account->point->save();
        }

        $mark->markable()->associate($markable);
        $mark->save();

        return $mark;
    }
}

?>