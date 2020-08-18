<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AnswerResource;
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

        $answer = null;
        $account = null;
        $file = null;
        $accountId = $request->accountId;
        if ($request->account === 'learner') {
            $account = Learner::find($accountId);
        } else if ($request->account === 'parent') {
            $account = ParentModel::find($accountId);
        } else if ($request->account === 'facilitator') {
            $account = Facilitator::find($accountId);
        } else if ($request->account === 'professional') {
            $account = Professional::find($accountId);
        } else if ($request->account === 'school') {
            $account = School::find($accountId);
        } else {
            return response()->json([
                'message' => "unsuccessful. {$request->account} does not exist."
            ],422);
        }

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
            if ($account->user_id === auth()->id()) {
                DB::beginTransaction();
                
                if ($request->has('possible_answer_id') && 
                    $request->possible_answer_id !== ''  && 
                    is_null($request->possible_answer_id)) {
                    $answerPossibleAnswerId = (int) $request->possible_answer_id;
                } else {
                    $answerPossibleAnswerId = null;
                }

                $answer = $account->answers()->create([
                    'answer' => $request->answer,
                    'possible_answer_id' => $answerPossibleAnswerId,
                ]);

                $file = null;
                if ($request->hasFile('file')) {
                    $fileDetails = [];
                    $fileDetails = getFileDetails($request->file('file'));

                    $file = accountCreateFile(
                        $fileDetails['mime'],
                        $account, 
                        $fileDetails,
                        $answer
                    );
                }
            } else {
                return response()->json([
                    'message' => "unsuccessful. {$request->account} does not exist 0r does not belong to you."
                ]);
            }
    
            if ($item === 'riddle') {
                $riddle = Riddle::find($itemId);

                if ($answer && $riddle) {
                    $answer->answerable()->associate($riddle);
                    $answer->save();
    
                    DB::commit();
                    return response()->json([
                        'message' => "successful",
                        'answer' => new AnswerResource($answer),
                    ]);
                } else {
                    if($file){
                        Storage::delete($file->path);
                    }
                    DB::rollback();
                    return response()->json([
                        'message' => "unsuccessful. {$item} does not exist or answer was not created"
                    ]);
                }
            } else if ($item === 'question') {
                $question = Question::find($itemId);

                if ($answer && $question) {
                    $answer->answerable()->associate($question);
                    $answer->save();
    
                    DB::commit();
                    return response()->json([
                        'message' => "successful",
                        'answer' => new AnswerResource($answer),
                    ]);
                } else {
                    if($file){
                        Storage::delete($file->path);
                    }
                    DB::rollback();
                    return response()->json([
                        'message' => "unsuccessful. {$item} does not exist or answer was not created"
                    ]);
                }
            }
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

        $mainAnswer = null;
        $mainAccount = null;
        // $file = null;
        if ($request->account === 'learner') {
            $mainAccount = Learner::find($request->accountId);
        } else if ($request->account === 'parent') {
            $mainAccount = ParentModel::find($request->accountId);
        } else if ($request->account === 'facilitator') {
            $mainAccount = Facilitator::find($request->accountId);
        } else if ($request->account === 'professional') {
            $mainAccount = Professional::find($request->accountId);
        } else if ($request->account === 'school') {
            $mainAccount = School::find($request->accountId);
        }

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
