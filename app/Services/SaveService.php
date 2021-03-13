<?php

namespace App\Services;

use App\Exceptions\AccountNotFoundException;
use App\Exceptions\SaveException;
use App\YourEdu\Answer;
use App\YourEdu\Comment;
use App\YourEdu\Discussion;
use App\YourEdu\Post;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class SaveService
{
    public function saveCreate($account,$accountId,$item, $itemId,$id,$adminId)
    {
        $mainAccount = getYourEduModel($account,$accountId);
        if (is_null($mainAccount)) {
            throw new AccountNotFoundException("{$account} not found with id {$accountId}");
        }

        $mainItem = getYourEduModel($item,$itemId);
        if (is_null($mainItem)) {
            throw new AccountNotFoundException("{$item} not found with id {$itemId}");
        }

        $save = $mainAccount->savesMade()->create([
            'user_id' => $id
        ]);

        $save->saveable()->associate($mainItem);
        $save->save();
        
        if ($adminId) {
            $admin = getYourEduModel('admin',$adminId);
            if (!is_null($admin)) {
                (new ActivityTrackService())->trackActivity(
                    $save,$save->savedby,$admin,__METHOD__
                );
            }
        }

        return $save;
    }

    public function saveDelete($saveId,$id,$adminId)
    {        
        $save = getYourEduModel('save',$saveId);
        if (is_null($save)) {
            throw new AccountNotFoundException("save not found with id {$saveId}");
        }
        if ($save->user_id !== $id) {
            throw new SaveException('you cannot unsave a save you do not own.');
        }
        
        if ($adminId) {
            $admin = getYourEduModel('admin',$adminId);
            if (!is_null($admin)) {
                (new ActivityTrackService())->trackActivity(
                    $save,$save->savedby,$admin, __METHOD__
                );
            }
        }

        $save->delete();

        return "successful";
    }

    public function userSavedGet($type)
    {
        $saves = new Collection();
        if ($type === 'comments') {
            $saves = $saves->merge($this->getSavedComments());
        } else if ($type === 'answers') {
            $saves = $saves->merge($this->getSavedAnswers());
        } else if ($type === 'posts') {
            $saves = $saves->merge($this->getSavedPosts());
        } else if ($type === 'discussions') {
            $saves = $saves->merge($this->getSavedDiscussions());
        } else {
            $saves = $saves->merge($this->getSavedPosts());
            $saves = $saves->merge($this->getSavedAnswers());
            $saves = $saves->merge($this->getSavedComments());
            $saves = $saves->merge($this->getSavedDiscussions());
        }

        return $saves;
    }

    private function getSavedPosts()
    {
        return Post::with(['questions','activities','riddles','beenSaved',
            'poems.poemSections','books','addedby.profile.images',
            'files','audios','videos'])
            ->whereHas('beenSaved',function(Builder $query){
                $query->where('user_id', auth()->id());
            })->latest()->get();
    }

    private function getSavedComments()
    {
        return Comment::with(['beenSaved','images','commentedby.profile.images',
            'files','audios','videos'])
            ->whereHas('beenSaved',function(Builder $query){
                $query->where('user_id', auth()->id());
            })->latest()->get();
    }

    private function getSavedDiscussions()
    {
        return Discussion::with(['beenSaved','images','raisedby.profile.images',
            'files','audios','videos','likes'])
            ->whereHas('beenSaved',function(Builder $query){
                $query->where('user_id', auth()->id());
            })->latest()->get();
    }

    private function getSavedAnswers()
    {
        return Answer::with(['beenSaved','images','answeredby.profile.images',
            'files','audios','videos'])
            ->whereHas('beenSaved',function(Builder $query){
                $query->where('user_id', auth()->id());
            })->latest()->get();
    }
}