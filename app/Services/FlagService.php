<?php

namespace App\Services;

use App\DTOs\ActivityTrackDTO;
use App\DTOs\FlagDTO;
use App\Events\DeleteFlag;
use App\Events\NewFlag;
use App\Exceptions\AccountNotFoundException;
use App\Exceptions\FlagException;
use App\Jobs\CreateFlagsForOthersJob;
use App\Traits\ServiceTrait;
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
    use ServiceTrait;

    public function deleteFlag(FlagDTO $flagDTO)
    {
        $flag = $this->getModel('flag', $flagDTO->flagId);

        $flagDTO = $flagDTO->withFlag($flag);

        $this->checkOwnerShip($flagDTO);

        $flag->delete();

        $flagDTO->method = __METHOD__;
        $this->trackSchoolAdmin($flagDTO);

        $this->setItemForBroadcast($flagDTO);

        $flagDTO->methodType = 'deleted';
        $this->broadcastFlag($flagDTO);
    }

    private function setItemForBroadcast($flagDTO)
    {
        if ($flagDTO->item) {
            return $flagDTO;
        }

        if ($flagDTO->flaggable) {
            $flagDTO->item = class_basename_lower($flagDTO->flaggable);
            $flagDTO->itemId = $flagDTO->flaggable->id;
            return $flagDTO;
        }

        if (is_null($flagDTO->flag)) {
            return $flagDTO;
        }

        $flagDTO->item = class_basename_lower($flagDTO->flag->flaggable);
        $flagDTO->itemId = $flagDTO->flag->flaggable->id;
        return $flagDTO;
    }

    private function associateFlagToItem($flagDTO)
    {
        $flagDTO->flag->flaggable()->associate($flagDTO->flaggable);
        $flagDTO->flag->save();

        return $flagDTO->flag;
    }

    private function makeFlag($flagDTO)
    {
        return $flagDTO->flaggedby->flagsRaised()->create([
            'user_id' => $flagDTO->userId,
            'flag_id' => $flagDTO->flagId,
            'reason' => $flagDTO->reason,
            'status' => "PENDING",
        ]);
    }

    private function setFlaggedby($flagDTO)
    {
        if ($flagDTO->flaggedby) {
            return $flagDTO;
        }

        return $flagDTO->withFlaggedby(
            $this->getModel($flagDTO->account, $flagDTO->accountId)
        );
    }

    private function setFlaggable($flagDTO)
    {
        if ($flagDTO->flaggable) {
            return $flagDTO;
        }

        if ($flagDTO->flag) {
            return $flagDTO->withFlaggable($flagDTO->flag->flaggable);
        }

        return $flagDTO->withFlaggable(
            $this->getModel($flagDTO->item, $flagDTO->itemId)
        );
    }

    private function trackSchoolAdmin($flagDTO)
    {
        if (is_null($flagDTO->adminId)) {
            return;
        }

        $admin = $this->getModel('admin', $flagDTO->adminId);

        (new ActivityTrackService())->trackActivity(
            ActivityTrackDTO::createFromData(
                activity: $flagDTO->flag,
                activityfor: $flagDTO->flag->flaggedby,
                performedby: $admin,
                action: $flagDTO->method
            )
        );
    }

    private function checkOwnership($flagDTO)
    {
        if ($flagDTO->flag->user_id == $flagDTO->userId) {
            return;
        }

        $this->throwFlagException('you cannot unflag a flag you do not own.');
    }

    private function broadcastFlag($flagDTO)
    {
        $event = $this->getEvent($flagDTO);

        broadcast($event)->toOthers();
    }

    private function getEvent($flagDTO)
    {
        if ($flagDTO->methodType === 'created') {
            return new NewFlag($flagDTO);
        }

        return new DeleteFlag($flagDTO);
    }

    private function throwFlagException($message, $data = null)
    {
        throw new FlagException(
            message: $message,
            data: $data
        );
    }

    private function dontFlagOwnedItem(FlagDTO $flagDTO)
    {
        if ($flagDTO->flaggable->user_id !== $flagDTO->userId) {
            return;
        }

        $this->throwFlagException(
            message: "you cannot flag your own account",
            data: $flagDTO
        );
    }

    private function preventDoubleFlagging($flagDTO)
    {
        if (!Arr::has($flagDTO->flaggable->flags->pluck('user_id'), $flagDTO->userId)) {
            return;
        }

        $this->throwFlagException(
            message: "you cannot flag something you have already flagged.",
            data: $flagDTO
        );
    }

    public function createFlag(FlagDTO $flagDTO)
    {
        $flagDTO = $this->setFlaggedby($flagDTO);

        $flagDTO = $this->setFlaggable($flagDTO);

        $this->dontFlagOwnedItem($flagDTO);

        $this->preventDoubleFlagging($flagDTO);

        $flag = $this->makeFlag($flagDTO);

        $flagDTO = $flagDTO->withFlag($flag);

        $flag = $this->associateFlagToItem($flagDTO);

        $this->flagForOthers($flagDTO);

        $flagDTO->method = __METHOD__;
        $this->trackSchoolAdmin($flagDTO);

        $flagDTO = $this->setItemForBroadcast($flagDTO);

        $flagDTO->methodType = 'created';
        $this->broadcastFlag($flagDTO);

        return $flag;
    }

    private function flagForOthers($flagDTO)
    {
        if (!in_array($flagDTO->flaggedby->accountType, ['parent', 'school'])) {
            return;
        }

        if ($flagDTO->flaggedby->accountType === 'parent') {
            array_push($flagDTO->flaggedbys, ...$flagDTO->flaggedby->wards);
        }

        if ($flagDTO->flaggedby->accountType === 'school') {
            array_push($flagDTO->flaggedbys, ...$flagDTO->flaggedby->learners);
        }

        CreateFlagsForOthersJob::dispatch($flagDTO);
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
        return Profile::with(['profileable.flags', 'user', 'images'])
            ->whereHasMorph('profileable', '*', function (Builder $query) {
                $query->whereHas('flags', function (Builder $query) {
                    $query->where('user_id', auth()->id());
                });
            })->latest()->get();
    }

    private function getFlaggedPosts()
    {
        return Post::with([
            'questions', 'activities', 'riddles', 'addedby.flags',
            'poems.poemSections', 'books', 'addedby.profile.images',
            'files', 'audios', 'videos'
        ])
            ->whereHas('flags', function (Builder $query) {
                $query->where('user_id', auth()->id());
            })->latest()->get();
    }

    private function getFlaggedDiscussions()
    {
        return Discussion::with([
            'raisedby.profile.images',
            'files', 'audios', 'videos', 'images', 'likes'
        ])
            ->whereHas('flags', function (Builder $query) {
                $query->where('user_id', auth()->id());
            })->latest()->get();
    }

    private function getFlaggedComments()
    {
        return Comment::with([
            'commentedby.flags', 'images', 'commentedby.profile.images',
            'files', 'audios', 'videos'
        ])
            ->whereHas('flags', function (Builder $query) {
                $query->where('user_id', auth()->id());
            })->latest()->get();
    }

    private function getFlaggedAnswers()
    {
        return Answer::with([
            'answeredby.flags', 'images', 'answeredby.profile.images',
            'files', 'audios', 'videos'
        ])
            ->whereHas('flags', function (Builder $query) {
                $query->where('user_id', auth()->id());
            })->latest()->get();
    }
}
