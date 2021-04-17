<?php

namespace App\Services;

use App\DTOs\ActivityTrackDTO;
use App\DTOs\SaveDTO;
use App\Events\DeleteSave;
use App\Events\NewSave;
use App\Exceptions\SaveException;
use App\Traits\ServiceTrait;
use App\YourEdu\Answer;
use App\YourEdu\Comment;
use App\YourEdu\Discussion;
use App\YourEdu\Post;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class SaveService
{
    use ServiceTrait;

    public function createSave(SaveDTO $saveDTO)
    {
        $saveDTO = $this->setSavedby($saveDTO);

        $saveDTO = $this->setSaveable($saveDTO);

        $save = $this->makeSave($saveDTO);

        $saveDTO = $saveDTO->withSave($save);

        $save = $this->associateSaveToItem($saveDTO);
        
        $saveDTO->method = __METHOD__;
        $this->trackSchoolAdmin($saveDTO);

        $saveDTO = $this->setItemForBroadcast($saveDTO);

        $saveDTO->methodType = 'created';
        $this->broadcastSave($saveDTO);

        return $save;
    }

    private function setItemForBroadcast($saveDTO)
    {
        if ($saveDTO->item) {
            return $saveDTO;
        }

        if ($saveDTO->saveable) {
            $saveDTO->item = class_basename_lower($saveDTO->saveable);
            $saveDTO->itemId = $saveDTO->saveable->id;
            return $saveDTO;
        }

        if (is_null($saveDTO->save)) {
            return $saveDTO;
        }

        $saveDTO->item = class_basename_lower($saveDTO->save->saveable);
        $saveDTO->itemId = $saveDTO->save->saveable->id;
        return $saveDTO;
    }

    private function associateSaveToItem($saveDTO)
    {
        $saveDTO->save->saveable()->associate($saveDTO->saveable);
        $saveDTO->save->save();

        return $saveDTO->save;
    }

    private function makeSave($saveDTO)
    {
        return $saveDTO->savedby->savesMade()->create([
            'user_id' => $saveDTO->userId
        ]);
    }

    private function broadcastSave($saveDTO)
    {
        $event = $this->getEvent($saveDTO);

        broadcast($event)->toOthers();
    }

    private function getEvent($saveDTO)
    {
        if ($saveDTO->methodType === 'created') {
            return new NewSave($saveDTO);
        }

        return new DeleteSave($saveDTO);
    }

    private function setSavedby($saveDTO)
    {
        if ($saveDTO->savedby) {
            return $saveDTO;
        }

        return $saveDTO->withSavedby(
            $this->getModel($saveDTO->account,$saveDTO->accountId)
        );
    }

    private function setSaveable($saveDTO)
    {
        if ($saveDTO->saveable) {
            return $saveDTO;
        }

        if ($saveDTO->save) {
            return $saveDTO->withSaveable($saveDTO->save->saveable);
        }

        return $saveDTO->withSaveable(
            $this->getModel($saveDTO->item,$saveDTO->itemId)
        );
    }

    private function trackSchoolAdmin($saveDTO)
    {
        if (is_null($saveDTO->adminId)) {
            return;
        }

        $admin = $this->getModel('admin',$saveDTO->adminId);

        (new ActivityTrackService())->trackActivity(
            ActivityTrackDTO::createFromData(
                activity: $saveDTO->save,
                activityfor: $saveDTO->save->savedby,
                performedby: $admin,
                action: $saveDTO->method
            )
        );
    }

    public function deleteSave(SaveDTO $saveDTO)
    {
        $save = $this->getModel('save',$saveDTO->saveId);

        $saveDTO = $saveDTO->withSave($save);
        
        $this->checkOwnerShip($saveDTO);

        $save->delete();
        
        $saveDTO->method = __METHOD__;
        $this->trackSchoolAdmin($saveDTO);

        $this->setItemForBroadcast($saveDTO);

        $saveDTO->methodType = 'deleted';
        $this->broadcastSave($saveDTO);
    }

    private function checkOwnership($saveDTO)
    {
        if ($saveDTO->save->user_id == $saveDTO->userId) {
            return;
        }

        $this->throwSaveException('you cannot unsave a save you do not own.');
    }

    private function throwSaveException($message, $data = null)
    {
        throw new SaveException(
            message: $message,
            data: $data
        );
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

    private function throwLikeException($message, $data = null)
    {
        throw new SaveException(
            message: $message,
            data: $data
        );
    }
}