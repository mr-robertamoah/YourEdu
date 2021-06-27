<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MarkResource;
use App\Http\Resources\RemarkResource;
use App\Services\MarkService;
use App\YourEdu\Answer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MarkController extends Controller
{
    public function markCreate(Request $request,$answer, $answerId)
    {
        $mainAccount = getYourEduModel($request->account,$request->accountId);
        
        if ($mainAccount) {
            $mainItem = null;
            if ($answer === 'answer') {
                $mainItem = Answer::find($answerId);
            }
            
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

                    $mark = (new MarkService())->createMark($request,$mainAccount,$mainItem);
                    if (is_null($mark)) {
                        return response()->json([
                            'message' => "unsuccessful, mark was not created",
                        ],422);
                    }
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
                'message' => "{$request->account} does not exit."
            ], 422);
        }
    }

    public function getAnswerMarks($answerId)
    {
        $answer = getYourEduModel('answer', $answerId);

        if (is_null($answer)) {
            return response()->json([
                'message' => "unsuccessful, answer not found",
            ],422);
        }

        return RemarkResource::collection($answer->marks()->latest()->paginate(10));
    }
}
