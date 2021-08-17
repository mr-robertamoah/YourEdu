<?php

namespace App\Http\Controllers\Api;

use App\DTOs\MarkDTO;
use App\Http\Controllers\Controller;
use App\Http\Resources\MarkResource;
use App\Http\Resources\RemarkResource;
use App\Services\MarkService;
use App\YourEdu\Answer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MarkController extends Controller
{
    public function markCreate(Request $request)
    {
        try {
            DB::beginTransaction();

            $mark = (new MarkService())->createMark(
                MarkDTO::createFromRequest($request)
            );

            DB::commit();

            return response()->json([
                'message' => "successful",
                'mark' => new MarkResource($mark),
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            // return response()->json([
            //     'message' => "unsuccessful"
            // ],422);
            throw $th;
        }
    }

    public function updateMark(Request $request)
    {
        try {
            $mark = (new MarkService)->updateMark(
                MarkDTO::createFromRequest($request)
            );

            return response()->json([
                'message' => 'successful',
                'mark' => new MarkResource($mark)
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function deleteMark(Request $request)
    {
        try {
            (new MarkService)->deleteMark(
                MarkDTO::createFromRequest($request)
            );

            return response()->json([
                'message' => 'successful',
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function getAnswerMarks($answerId)
    {
        $answer = getYourEduModel('answer', $answerId);

        if (is_null($answer)) {
            return response()->json([
                'message' => "unsuccessful, answer not found",
            ], 422);
        }

        return RemarkResource::collection($answer->marks()->latest()->paginate(10));
    }
}
