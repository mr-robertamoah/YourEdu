<?php

namespace App\Http\Controllers\Api;

use App\DTOs\AnswerDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateAnswerRequest;
use App\Http\Resources\AnswerResource;
use App\Http\Resources\MarkedAnswerResource;
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

    public function createAnswer(CreateAnswerRequest $request)
    {
        try {
            DB::beginTransaction();

            $answer = (new AnswerService())->createAnswer(
                AnswerDTO::createFromRequest($request)
            );

            DB::commit();

            return response()->json([
                'message' => "successful",
                'answer' => new AnswerResource($answer),
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            // return response()->json([
            //     'message' => "Unsuccessful. Something might have gone wrong. Please try again later."
            // ]);
            throw $th;
        }
    }

    public function updateAnswer(Request $request)
    {
        try {
            DB::beginTransaction();

            $answer = (new AnswerService)->updateAnswer(
                AnswerDTO::createFromRequest($request)
            );

            DB::commit();
            return response()->json([
                'message' => "successful",
                'answer' => $answer->isForAssessment() ?
                    new MarkedAnswerResource($answer) :
                    new AnswerResource($answer),
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            // return response()->json([
            //     'message' => "Unsuccessful. Something might have gone wrong. Please try again later."
            // ]);
            throw $th;
        }
    }

    public function deleteAnswer(Request $request)
    {

        try {

            (new AnswerService)->deleteAnswer(
                AnswerDTO::createFromRequest($request)
            );

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

        if ($item === 'question') {
            $mainItem = Question::find($itemId);
        } else if ($item === 'riddle') {
            $mainItem = Riddle::find($itemId);
        } else {
            return response()->json([
                'message' => "unsuccessful. {$item} is not valid for comments."
            ]);
        }

        if ($mainItem) {
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
            ], 422);
        }
        return response()->json([
            'message' => "successful",
            'answer' => new AnswerResource($item)
        ]);
    }
}
