<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\FlaggedResource;
use App\Http\Resources\FlagResource;
use App\YourEdu\Answer;
use App\YourEdu\ClassModel;
use App\YourEdu\Comment;
use App\YourEdu\Discussion;
use App\YourEdu\Facilitator;
use App\YourEdu\Flag;
use App\YourEdu\Learner;
use App\YourEdu\ParentModel;
use App\YourEdu\Post;
use App\YourEdu\Professional;
use App\YourEdu\Profile;
use App\YourEdu\Read;
use App\YourEdu\School;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use \Debugbar;

class FlagController extends Controller
{
    //

    public function flagDelete(Request $request,$flag)
    {
        
        $mainFlag = Flag::find($flag);
        // dd($request->route('like'));
        if ($mainFlag) {
            if ($mainFlag->user_id !== auth()->id()) {
                return response()->json([
                    'message' => 'you cannot unflag a flag you do not own.'
                ]);
            }
        } else {
            return response()->json([
                'message' => 'there is no such flag.'
            ]);
        }

        try {
            $mainFlag->delete();
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

    public function flagCreate(Request $request,$item, $itemId)
    {
        $mainItem = null;
        $mainAccount = null;
        $account = $request->account;
        $accountId = $request->accountId;
        $flag = null;
        // return $itemId;
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
            if ($item === 'post') {
                $mainItem = Post::find($itemId);
            } else if ($item === 'comment') {
                $mainItem = Comment::find($itemId);
            } else if ($item === 'school') {
                $mainItem = School::find($itemId);
            } else if ($item === 'class') {
                $mainItem = ClassModel::find($itemId);
            } else if ($item === 'answer') {
                $mainItem = Answer::find($itemId);
            } else if ($item === 'learner') {
                $mainItem = Learner::find($itemId);
                if ($mainItem && $mainItem->user_id === auth()->id()) {
                    return response()->json([
                        'message' => "unsuccessful, you cannot flag your own account",
                    ],422);
                }
            } else if ($item === 'facilitator') {
                $mainItem = Facilitator::find($itemId);
                if ($mainItem && $mainItem->user_id === auth()->id()) {
                    return response()->json([
                        'message' => "unsuccessful, you cannot flag your own account",
                    ],422);
                }
            } else if ($item === 'parent') {
                $mainItem = ParentModel::find($itemId);
                if ($mainItem && $mainItem->user_id === auth()->id()) {
                    return response()->json([
                        'message' => "unsuccessful, you cannot flag your own account",
                    ],422);
                }
            } else if ($item === 'school') {
                $mainItem = School::find($itemId);
                if ($mainItem && $mainItem->user_id === auth()->id()) {
                    return response()->json([
                        'message' => "unsuccessful, you cannot flag your own account",
                    ],422);
                }
            } else if ($item === 'professional') {
                $mainItem = Professional::find($itemId);
                if ($mainItem && $mainItem->user_id === auth()->id()) {
                    return response()->json([
                        'message' => "unsuccessful, you cannot flag your own account",
                    ],422);
                }
            } else if ($item === 'discussion') {
                $mainItem = Discussion::find($itemId);
            } else if ($item === 'read') {
                $mainItem = Read::find($itemId);
            }
            // return $mainAccount;
            try {
                if ($mainItem) {
                    DB::beginTransaction();
                    $flag = $mainAccount->flagsRaised()->create([
                        'user_id' => auth()->id(),
                        'status' => 'PENDING',
                        'reason' => $request->reason,
                    ]);
                    if (Arr::has($mainItem->flags->pluck('user_id'),auth()->id())) {
                        if ($mainItem->user_id === auth()->id()) {
                            return response()->json([
                                'message' => "unsuccessful, you cannot flag something you have already flagged.",
                            ],422);
                        }
                    }
                    $flag->flaggable()->associate($mainItem);
                    $flag->save();

                    DB::commit();
                    return response()->json([
                        'message' => "successful",
                        'flag' => new FlagResource($flag),
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

    public function userFlaggedGet(Request $request)
    {
        $type = $request->type;
        $flags = new Collection();
        if ($type === 'accounts') {
            $flags = $flags->merge($this->getFlaggedProfiles());
        } else if ($type === 'comments') {
            $flags = $flags->merge($this->getFlaggedComments());
        } else if ($type === 'answers') {
            $flags = $flags->merge($this->getFlaggedAnswers());
        } else if ($type === 'posts') {
            $flags = $flags->merge($this->getFlaggedPosts());
        } else {
            $flags = $flags->merge($this->getFlaggedProfiles());
            $flags = $flags->merge($this->getFlaggedPosts());
            $flags = $flags->merge($this->getFlaggedAnswers());
            $flags = $flags->merge($this->getFlaggedComments());
        }

        Debugbar::info($flags);
        return FlaggedResource::collection(paginate($flags->sortByDesc('updated_at'), 5));
    }

    public function getFlaggedProfiles()
    {
        return Profile::with(['profileable.flags','user','images'])
            ->whereHasMorph('profileable','*',function(Builder $query){
            $query->whereHas('flags',function(Builder $query){
                $query->where('user_id', auth()->id());
            });
        })->latest()->get();
    }

    public function getFlaggedPosts()
    {
        return Post::with(['questions','activities','riddles','postedby.flags',
            'poems.poemSections','books','postedby.profile.images',
            'files','audios','videos'])
            ->whereHas('flags',function(Builder $query){
                $query->where('user_id', auth()->id());
            })->latest()->get();
    }

    public function getFlaggedComments()
    {
        return Comment::with(['commentedby.flags','images','commentedby.profile.images',
            'files','audios','videos'])
            ->whereHas('flags',function(Builder $query){
                $query->where('user_id', auth()->id());
            })->latest()->get();
    }

    public function getFlaggedAnswers()
    {
        return Answer::with(['answeredby.flags','images','answeredby.profile.images',
            'files','audios','videos'])
            ->whereHas('flags',function(Builder $query){
                $query->where('user_id', auth()->id());
            })->latest()->get();
    }
    
}
