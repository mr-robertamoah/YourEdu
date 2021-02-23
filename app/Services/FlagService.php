<?php

namespace App\Services;

use App\Exceptions\AccountNotFoundException;
use App\Exceptions\FlagException;
use App\YourEdu\Answer;
use App\YourEdu\Comment;
use App\YourEdu\Discussion;
use App\YourEdu\Post;
use App\YourEdu\Profile;
use Illuminate\Database\Eloquent\Builder;
use \Debugbar;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;

class FlagService
{
    public function flagDelete($flagId,$id)
    {
        $flag = getYourEduModel('flag',$flagId);
        if (is_null($flag)) {
            throw new AccountNotFoundException("flag not found with id {$flagId}");
        }
        if ($flag->user_id !== $id) {
            throw new FlagException('you cannot unflag a flag you do not own.');
        }

        $flag->delete();

        return 'successful';
    }

    private function dontFlagOwn($mainItem,$item,$id)
    {
        if ($item === 'learner') {
            if ($mainItem && $mainItem->user_id === $id) {
                throw new FlagException("you cannot flag your own account");
            }
        } else if ($item === 'facilitator') {
            if ($mainItem && $mainItem->user_id === $id) {
                throw new FlagException("you cannot flag your own account");
            }
        } else if ($item === 'parent') {
            if ($mainItem && $mainItem->user_id === $id) {
                throw new FlagException("you cannot flag your own account");
            }
        } else if ($item === 'school') {
            if ($mainItem && $mainItem->user_id === $id) {
                throw new FlagException("you cannot flag your own account");
            }
        } else if ($item === 'professional') {
            if ($mainItem && $mainItem->user_id === $id) {
                throw new FlagException("you cannot flag your own account");
            }
        }
    }

    public function flagCreate($account,$accountId,$item, $itemId,$reason,$id)
    {
        $mainAccount = getYourEduModel($account,$accountId);
        if (is_null($mainAccount)) {
            throw new AccountNotFoundException("{$account} not found with id {$accountId}");
        }

        $mainItem = getYourEduModel($item,$itemId);
        if (is_null($mainItem)) {
            throw new AccountNotFoundException("{$item} not found with id {$itemId}");
        }

        $this->dontFlagOwn($mainItem,$item,$id);

        if (Arr::has($mainItem->flags->pluck('user_id'),$id)) {
            throw new FlagException("you cannot flag something you have already flagged.");
        }

        $flag = $mainAccount->flagsRaised()->create([
            'user_id' => $id,
            'status' => 'PENDING',
            'reason' => $reason,
        ]);

        $flag->flaggable()->associate($mainItem);
        $flag->save();

        return $flag;
    }

    public function userFlaggedGet($type)
    {
        $flags = new Collection();
        if ($type === 'accounts') {
            $flags = $flags->merge($this->getFlaggedProfiles());
        } else if ($type === 'comments') {
            $flags = $flags->merge($this->getFlaggedComments());
        } else if ($type === 'answers') {
            $flags = $flags->merge($this->getFlaggedAnswers());
        } else if ($type === 'posts') {
            $flags = $flags->merge($this->getFlaggedPosts());
        } else if ($type === 'discussions') {
            $flags = $flags->merge($this->getFlaggedDiscussions());
        } else {
            $flags = $flags->merge($this->getFlaggedProfiles());
            $flags = $flags->merge($this->getFlaggedPosts());
            $flags = $flags->merge($this->getFlaggedAnswers());
            $flags = $flags->merge($this->getFlaggedComments());
        }

        Debugbar::info($flags);
        return $flags;
    }

    private function getFlaggedProfiles()
    {
        return Profile::with(['profileable.flags','user','images'])
            ->whereHasMorph('profileable','*',function(Builder $query){
            $query->whereHas('flags',function(Builder $query){
                $query->where('user_id', auth()->id());
            });
        })->latest()->get();
    }

    private function getFlaggedPosts()
    {
        return Post::with(['questions','activities','riddles','postedby.flags',
            'poems.poemSections','books','postedby.profile.images',
            'files','audios','videos'])
            ->whereHas('flags',function(Builder $query){
                $query->where('user_id', auth()->id());
            })->latest()->get();
    }

    private function getFlaggedDiscussions()
    {
        return Discussion::with(['raisedby.profile.images',
            'files','audios','videos','images','likes'])
            ->whereHas('flags',function(Builder $query){
                $query->where('user_id', auth()->id());
            })->latest()->get();
    }

    private function getFlaggedComments()
    {
        return Comment::with(['commentedby.flags','images','commentedby.profile.images',
            'files','audios','videos'])
            ->whereHas('flags',function(Builder $query){
                $query->where('user_id', auth()->id());
            })->latest()->get();
    }

    private function getFlaggedAnswers()
    {
        return Answer::with(['answeredby.flags','images','answeredby.profile.images',
            'files','audios','videos'])
            ->whereHas('flags',function(Builder $query){
                $query->where('user_id', auth()->id());
            })->latest()->get();
    }
}