<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\LikeResource;
use App\YourEdu\Admin;
use App\YourEdu\Answer;
use App\YourEdu\ClassModel;
use App\YourEdu\Comment;
use App\YourEdu\Discussion;
use App\YourEdu\Facilitator;
use App\YourEdu\Keyword;
use App\YourEdu\Learner;
use App\YourEdu\Lesson;
use App\YourEdu\Like;
use App\YourEdu\Mark;
use App\YourEdu\ParentModel;
use App\YourEdu\Post;
use App\YourEdu\Professional;
use App\YourEdu\Read;
use App\YourEdu\School;
use App\YourEdu\Word;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LikeController extends Controller
{
    //

    public function likeDelete(Request $request,$like)
    {
        
        $mainLike = Like::find($like);
        // dd($request->route('like'));
        if ($mainLike) {
            if ($mainLike->user_id !== auth()->id()) {
                return response()->json([
                    'message' => 'you cannot unlike a like you do not own.'
                ]);
            }
        } else {
            return response()->json([
                'message' => 'there is no such like.'
            ]);
        }

        try {
            $mainLike->delete();
            return response()->json([
                'message' => "successful"
            ]);
        } catch (\Throwable $th) {
            throw $th;
            // return response()->json([
            //     'message' => "successful"
            // ],422);
        }
    }

    public function likeCreate(Request $request,$item, $itemId)
    {
        $mainItem = null;
        $mainAccount = null;
        $account = $request->account;
        $accountId = $request->accountId;
        $like = null;
        // return $itemId;
        if ($account === 'learner') {
            $mainAccount = Learner::find($accountId);
        } else if ($account === 'parent') {
            $mainAccount = ParentModel::find($accountId);
        } else if ($account === 'facilitator') {
            $mainAccount = Facilitator::find($accountId);
        } else if ($account === 'professional') {
            $mainAccount = Professional::find($accountId);
        } else if ($account === 'admin') {
            $mainAccount = Admin::find($accountId);
        }

        if ($mainAccount) {
            if ($item === 'post') {
                $mainItem = Post::find($itemId);
            } else if ($item === 'comment') {
                $mainItem = Comment::find($itemId);
            } else if ($item === 'school') {
                $mainItem = School::find($itemId);
            } else if ($item === 'class') {
                $mainItem = ClassModel::find($itemId);
            } else if ($item === 'word') {
                $mainItem = Word::find($itemId);
            } else if ($item === 'keyword') {
                $mainItem = Keyword::find($itemId);
            } else if ($item === 'lesson') {
                $mainItem = Lesson::find($itemId);
            } else if ($item === 'character') {
                $mainItem = Comment::find($itemId);
            } else if ($item === 'expression') {
                $mainItem = Comment::find($itemId);
            } else if ($item === 'discussion') {
                $mainItem = Discussion::find($itemId);
            } else if ($item === 'read') {
                $mainItem = Read::find($itemId);
            } else if ($item === 'answer') {
                $mainItem = Answer::find($itemId);
            } else if ($item === 'mark') {
                $mainItem = Mark::find($itemId);
            }
            // return $mainAccount;
            try {
                if ($mainItem) {
                    DB::beginTransaction();
                    $like = $mainAccount->likings()->create([
                        'user_id' => auth()->id()
                    ]);
    
                    $like->likeable()->associate($mainItem);
                    $like->save();

                    DB::commit();
                    return response()->json([
                        'message' => "successful",
                        'like' => new LikeResource($like),
                    ]);
                } else {
                    return response()->json([
                        'message' => "{$item} does not exit."
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
