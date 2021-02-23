<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AnswerResource;
use App\Services\AnswerService;
use App\YourEdu\Answer;
use App\YourEdu\Facilitator;
use App\YourEdu\Learner;
use App\YourEdu\ParentModel;
use App\YourEdu\Professional;
use App\YourEdu\Question;
use App\YourEdu\Riddle;
use App\YourEdu\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AnswerController extends Controller
{
    //

    public function answerCreate(Request $request, $item, $itemId)
    {  
        $request->validate([
            'answer' => 'nullable|string',
            'account' => 'required|string',
            'accountId' => 'required|string',
            'file' => 'nullable|file',
        ]);
        
        if(is_null($request->answer) && is_null($request->file)){
            return response()->json([
                'message' => "unsuccessful. there is nothing to add as answer."
            ],422);
        }

        if ((is_null($request->answer) || $request->answer === '') &&
            (is_null($request->possible_answer_id) || $request->possible_answer_id === '')) {
                return response()->json([
                    'message' => "unsuccessful. answer or possible_answer_id needed."
                ],422);
        }

        $account = getYourEduModel($request->account,$request->accountId);

        if (!$account) {
            return response()->json([
                'message' => "unsuccessful. there is no such {$request->account}."
            ]);
        }

        if ($account->user_id !== auth()->id()) {
            return response()->json([
                'message' => "unsuccessful. you do not own this account."
            ]);
        }

        try {
            $file = null;
            DB::beginTransaction();
    
            $mainItem = null;
            if ($item === 'riddle') {
                $mainItem = Riddle::find($itemId);
            } else if ($item === 'question') {
                $mainItem = Question::find($itemId);
            }

            $answer = (new AnswerService())->createAnswer($request,$account,$mainItem);

            if (is_null($answer)) {
                DB::rollback();
                return response()->json([
                    'message' => "unsuccessful. answer was not created"
                ]);
            }

            DB::commit();
            return response()->json([
                'message' => "successful",
                'answer' => new AnswerResource($answer),
            ]);
        } catch (\Throwable $th) {
            if($file){
                Storage::delete($file->path);
            }
            DB::rollback();
            // return response()->json([
            //     'message' => "Unsuccessful. Something might have gone wrong. Please try again later."
            // ]);
            throw $th;
        }
        
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

    public function answerEdit(Request $request, $answer)
    {
        // return $answer;
        $request->validate([
            'answer' => 'nullable|string',
            // 'file' => 'nullable|file',
        ]);

        if ((is_null($request->answer) || $request->answer === '') &&
            (is_null($request->possible_answer_id) || $request->possible_answer_id === '')) {
                return response()->json([
                    'message' => "unsuccessful. answer or possible_answer_id needed."
                ],422);
        }
        
        // for now, answer must be required, on a later date, it wont be. but we will 
        //check to ensure that the update doesnt lead to an empty answer (without answer and file)

        $mainAccount = getYourEduModel($request->account,$request->accountId);

        if (!$mainAccount) {
            return response()->json([
                'message' => "unsuccessful. there is no such {$request->account}."
            ],422);
        }

        if ($mainAccount->user_id !== auth()->id()) {
            return response()->json([
                'message' => "unsuccessful. you do not own this account."
            ],422);
        }

        try {
                DB::beginTransaction();
                $mainAnswer = $mainAccount->answers()->where('id', $answer)->first();
                
                if (is_null($mainAnswer)) {
                    return response()->json([
                        'message' => "unsuccessful. answer not found."
                    ],422);
                }
                if ($mainAnswer->marks()->count() > 0) { //you cannot edit an answer that has been marked
                    return response()->json([
                        'message' => "unsuccessful. answer not found."
                    ],422);
                }
                if ($request->answer !== '' && !is_null($request->answer)) {
                    $mainAnswer->update([
                        'answer' => $request->answer
                    ]);
                }  

                if ($request->has('possible_answer_id') &&
                    $request->possible_answer_id !== $mainAnswer->possible_answer_id) {
                    $mainAnswer->possible_answer_id = $request->possible_answer_id;
                    $mainAnswer->save();
                }

                DB::commit();
                return response()->json([
                    'message' => "successful",
                    'answer' => new AnswerResource($mainAnswer),
                ]);        
        } catch (\Throwable $th) {
            DB::rollback();
            // return response()->json([
            //     'message' => "Unsuccessful. Something might have gone wrong. Please try again later."
            // ]);
            throw $th;
        }
    }

    public function answerDelete($answer)
    {
        $mainAnswer = Answer::find($answer);
        
        try {
            $mainAnswer->delete();
            return response()->json([
                'message' => "successful"
            ]);
        } catch (\Throwable $th) {
            throw $th;
            return response()->json([
                'message' => "unsuccessful"
            ]);
        }
    }

    public function answersGet($item, $itemId)
    {
        $mainItem = null;
        
        if($item === 'question'){
            $mainItem = Question::find($itemId);
        } else if($item === 'riddle'){
            $mainItem = Riddle::find($itemId);
        } else {
            return response()->json([
                'message' => "unsuccessful. {$item} is not valid for comments."
            ]);
        }

        if($mainItem){
            return AnswerResource::collection($mainItem->answers()->latest()->paginate(5));
        } else {
            return response()->json([
                'message' => "unsuccessful. {$item} was not found"
            ]);
        }
    }

    public function answerGet($answer)
    {
        $item = null;
        $item = Answer::find($answer);

        if (!$item) {
            return response()->json([
                'message' => 'unsuccessful, answer does not exist.'
            ],422);
        }
        return response()->json([
            'message' => "successful",
            'answer' => new AnswerResource($item)
        ]);
    }
}
