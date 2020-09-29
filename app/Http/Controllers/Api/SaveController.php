<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SavedResource;
use App\Http\Resources\SaveResource;
use App\YourEdu\Admin;
use App\YourEdu\Answer;
use App\YourEdu\Character;
use App\YourEdu\Comment;
use App\YourEdu\Discussion;
use App\YourEdu\Facilitator;
use App\YourEdu\Keyword;
use App\YourEdu\Learner;
use App\YourEdu\Lesson;
use App\YourEdu\ParentModel;
use App\YourEdu\Post;
use App\YourEdu\Professional;
use App\YourEdu\Read;
use App\YourEdu\Save;
use App\YourEdu\School;
use App\YourEdu\Word;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaveController extends Controller
{
    //

    public function saveDelete(Request $request,$save)
    {
        
        $mainSave = Save::find($save);
        if ($mainSave) {
            if ($mainSave->user_id !== auth()->id()) {
                return response()->json([
                    'message' => 'you cannot unsave a save you do not own.'
                ]);
            }
        } else {
            return response()->json([
                'message' => 'there is no such save.'
            ]);
        }

        try {
            $mainSave->delete();
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

    public function saveCreate(Request $request,$item, $itemId)
    {
        $mainItem = null;
        $mainAccount = null;
        $account = $request->account;
        $accountId = $request->accountId;
        $save = null;
        
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
        } else if ($account === 'school') {
            $mainAccount = School::find($accountId);
        }

        if ($mainAccount) {
            if ($item === 'post') {
                $mainItem = Post::find($itemId);
            } else if ($item === 'comment') {
                $mainItem = Comment::find($itemId);
            } else if ($item === 'word') {
                $mainItem = Word::find($itemId);
            } else if ($item === 'keyword') {
                $mainItem = Keyword::find($itemId);
            } else if ($item === 'lesson') {
                $mainItem = Lesson::find($itemId);
            } else if ($item === 'character') {
                $mainItem = Character::find($itemId);
            } else if ($item === 'expression') {
                $mainItem = Comment::find($itemId); //
            } else if ($item === 'discussion') {
                $mainItem = Discussion::find($itemId);
            } else if ($item === 'read') {
                $mainItem = Read::find($itemId);
            } else if ($item === 'answer') {
                $mainItem = Answer::find($itemId);
            }

            try {
                if ($mainItem) {
                    DB::beginTransaction();
                    $save = $mainAccount->savesMade()->create([
                        'user_id' => auth()->id()
                    ]);
    
                    $save->saveable()->associate($mainItem);
                    $save->save();

                    DB::commit();
                    return response()->json([
                        'message' => "successful",
                        'save' => new SaveResource($save),
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

    public function userSavedGet(Request $request)
    {
        $type = $request->type;
        $saves = new Collection();
        if ($type === 'comments') {
            $saves = $saves->merge($this->getSavedComments());
        } else if ($type === 'answers') {
            $saves = $saves->merge($this->getSavedAnswers());
        } else if ($type === 'posts') {
            $saves = $saves->merge($this->getSavedPosts());
        } else {
            $saves = $saves->merge($this->getSavedPosts());
            $saves = $saves->merge($this->getSavedAnswers());
            $saves = $saves->merge($this->getSavedComments());
        }

        return SavedResource::collection(paginate($saves->sortByDesc('updated_at'), 5));
    }

    public function getSavedPosts()
    {
        return Post::with(['questions','activities','riddles','beenSaved',
            'poems.poemSections','books','postedby.profile.images',
            'files','audios','videos'])
            ->whereHas('beenSaved',function(Builder $query){
                $query->where('user_id', auth()->id());
            })->latest()->get();
    }

    public function getSavedComments()
    {
        return Comment::with(['beenSaved','images','commentedby.profile.images',
            'files','audios','videos'])
            ->whereHas('beenSaved',function(Builder $query){
                $query->where('user_id', auth()->id());
            })->latest()->get();
    }

    public function getSavedAnswers()
    {
        return Answer::with(['beenSaved','images','answeredby.profile.images',
            'files','audios','videos'])
            ->whereHas('beenSaved',function(Builder $query){
                $query->where('user_id', auth()->id());
            })->latest()->get();
    }
}
