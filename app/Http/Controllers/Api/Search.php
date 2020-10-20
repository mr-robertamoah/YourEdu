<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SearchResource;
use App\User;
use App\YourEdu\Facilitator;
use App\YourEdu\Learner;
use App\YourEdu\ParentModel;
use App\YourEdu\Post;
use App\YourEdu\Professional;
use App\YourEdu\Profile;
use App\YourEdu\School;
use DebugBar\DebugBar;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class Search extends Controller
{
    //

    public function search(Request $request)
    {
        $search = $request->search;
        $parentsLearnerUserIds = [];
        $user = null;
        
        if ($request->has('user_id')) {
            $user = User::find($request->user_id);

            if ($user) {
                $learner = $user->learner;
                if ($learner && count($learner->parents)) {
                    $parentsLearnerUserIds = $learner->parents->pluck('user_id');
                }
                $parentsLearnerUserIds[] = $user->id;
            }
        }
        if ($request->has('searchType') && $request->searchType === 'profiles') {
            $searchItems = $this->getProfile($search,null,$parentsLearnerUserIds);
        }else if ($request->has('searchType') && $request->searchType === 'learners') {
            $searchItems = $this->getProfile($search,Learner::class,$parentsLearnerUserIds);
        } else if ($request->has('searchType') && $request->searchType === 'parents') {
            $searchItems = $this->getProfile($search,ParentModel::class,$parentsLearnerUserIds);
        } else if ($request->has('searchType') && $request->searchType === 'facilitators') {
            $searchItems = $this->getProfile($search,Facilitator::class,$parentsLearnerUserIds);
        } else if ($request->has('searchType') && $request->searchType === 'professionals') {
            $searchItems = $this->getProfile($search,Professional::class,$parentsLearnerUserIds);
        } else if ($request->has('searchType') && $request->searchType === 'schools') {
            $searchItems = $this->getProfile($search,School::class,$parentsLearnerUserIds);
        } else if ($request->has('searchType') && $request->searchType === 'posts') {
            $searchItems = $this->getPosts($search,$parentsLearnerUserIds);
        } else if ($request->has('searchType') && $request->searchType === 'reads') {
            
        } else if ($request->has('searchType') && $request->searchType === 'discussions') {

        } else {
            $searchItems = $this->getProfile($search,null,$parentsLearnerUserIds);
            $searchItems = $searchItems->merge($this->getPosts($search,$parentsLearnerUserIds));
        }

        // DebugBar::info($searchItems);
        return SearchResource::collection(paginate($searchItems->sortByDesc('updated_at'),5));
    }

    public function getProfile($search, $class = null, $parentsLearnerUserIds = [])
    {
        if (is_null($class)) {
            $class = '*';

            return Profile::with(['user','profileable','profileable.follows','images'])
                ->where('name','like',"%{$search}%")
                ->orWhereHasMorph('profileable', $class, function(Builder $query) use ($search) {
                    $query->where('name','like',"%{$search}%");
                })->orWhereHas('user',function(Builder $query) use ($search) {
                    $query->where('first_name','like',"%{$search}%")
                            ->orWhere('username','like',"%{$search}%")
                            ->orWhere('email','like',"%{$search}%")
                            ->orWhere('last_name','like',"%{$search}%")
                            ->orWhere('other_names','like',"%{$search}%");
                })->hasNoFlags($parentsLearnerUserIds)->get();
        }

        return Profile::with(['user','profileable','profileable.follows','images'])
                ->whereHasMorph('profileable', $class, function(Builder $query) use ($search) {
                    $query->where('name','like',"%{$search}%");
                })->get();

    }

    public function getPosts($search, $parentsLearnerUserIds = [])
    {
        return Post::with(['questions','activities','riddles',
            'poems.poemSections','books','postedby.profile.images'])
            ->where('content','like',"%{$search}%")
            ->orWhereHas('questions',function(Builder $query) use ($search) {
                $query->where('question','like',"%{$search}%");
            })
            ->orWhereHas('activities',function(Builder $query) use ($search) {
                $query->where('description','like',"%{$search}%");
            })
            ->orWhereHas('poems',function(Builder $query) use ($search) {
                $query->where('title','like',"%{$search}%")
                ->orWhere('about','like',"%{$search}%")
                ->orWhere('author','like',"%{$search}%")
                ->orWhereHas('poemSections',function(Builder $query) use ($search) {
                    $query->where('body','like',"%{$search}%");
                });
            })
            ->orWhereHas('riddles',function(Builder $query) use ($search) {
                $query->where('riddle','like',"%{$search}%")
                ->orWhere('author','like',"%{$search}%");
            })
            ->orWhereHas('books',function(Builder $query) use ($search) {
                $query->where('title','like',"%{$search}%")
                    ->orWhere('author','like',"%{$search}%")
                    ->orWhere('about','like',"%{$search}%");
            })->hasNoFlags($parentsLearnerUserIds)->hasPublished()->latest()->get();
    }
}
