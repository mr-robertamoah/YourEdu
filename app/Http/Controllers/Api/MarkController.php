<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MarkResource;
use App\YourEdu\Admin;
use App\YourEdu\Answer;
use App\YourEdu\Facilitator;
use App\YourEdu\Learner;
use App\YourEdu\ParentModel;
use App\YourEdu\Professional;
use App\YourEdu\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MarkController extends Controller
{
    //

    public function markCreate(Request $request,$answer, $answerId)
    {
        $mainItem = null;
        $mainAccount = null;
        $account = $request->account;
        $accountId = $request->accountId;

        if ($account === 'learner') {
            $mainAccount = Learner::find($accountId);
        } else if ($account === 'parent') {
            $mainAccount = ParentModel::find($accountId);
        } else if ($account === 'facilitator') {
            $mainAccount = Facilitator::find($accountId);
        } else if ($account === 'professional') {
            $mainAccount = Professional::find($accountId);
        } else if ($account === 'school') {
            $mainAccount = School::find($accountId);
        }

        if ($mainAccount) {
            if ($answer === 'answer') {
                $mainItem = Answer::find($answerId);
            }
            // return $mainAccount;
            try {
                if ($mainItem) {
                    $request->validate([
                        'remark' => 'nullable|string',
                        'state' => 'required|string',
                        'score' => 'required',
                        'score_over' => 'required',
                    ]);
                    DB::beginTransaction();
                    
                    if ($mainItem->marks()->where('user_id',auth()->id())->count()) {
                        return response()->json([
                            'message' => "unsuccessful, one of your accounts has already marked this answer",
                        ],422);
                    }

                    $mark = $mainAccount->marks()->create([
                        'user_id' => auth()->id(),
                        'answer_id' => $answerId,
                        'remark' => $request->remark,
                        'state' => strtoupper($request->state),
                        'score' => (int)$request->score,
                        'score_over' => (int)$request->score_over,
                    ]);
                    $point = $mainAccount->point->value;
                    $mainAccount->point()->update([
                        'value' => $point + 1
                    ]);
    
                    $mark->markable()->associate($mainItem);
                    $mark->save();

                    DB::commit();
                    return response()->json([
                        'message' => "successful",
                        'mark' => new MarkResource($mark),
                        'avg_score' => $mainItem->marks()->avg('score'),
                        'max_score' => $mainItem->marks()->max('score'),
                        'min_score' => $mainItem->marks()->min('score'),
                    ]);
                } else {
                    return response()->json([
                        'message' => "{$answer} does not exit."
                    ], 422);
                }
            } catch (\Throwable $th) {
                DB::rollback();
                // return response()->json([
                //     'message' => "unsuccessful"
                // ],422);
                throw $th;
            }
        } else {
            return response()->json([
                'message' => "{$account} does not exit."
            ], 422);
        }
    }
}
