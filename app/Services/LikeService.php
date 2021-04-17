<?php

namespace App\Services;

use App\DTOs\ActivityTrackDTO;
use App\DTOs\LikeDTO;
use App\Events\DeleteLike;
use App\Events\NewLike;
use App\Exceptions\AccountNotFoundException;
use App\Exceptions\LikeException;
use App\Traits\ServiceTrait;

class LikeService
{
    use ServiceTrait;

    public function createLike(LikeDTO $likeDTO)
    {
        $likeDTO = $this->setLikedby($likeDTO);

        $likeDTO = $this->setlikeable($likeDTO);

        $like = $this->makeLike($likeDTO);

        $likeDTO = $likeDTO->withLike($like);

        $like = $this->associateLikeToItem($likeDTO);
        
        $likeDTO->method = __METHOD__;
        $this->trackSchoolAdmin($likeDTO);

        $likeDTO = $this->setItemForBroadcast($likeDTO);

        $likeDTO->methodType = 'created';
        $this->broadcastLike($likeDTO);
        
        return $like;
    }

    private function setItemForBroadcast($likeDTO)
    {
        if ($likeDTO->item) {
            return $likeDTO;
        }

        if ($likeDTO->likeable) {
            $likeDTO->item = class_basename_lower($likeDTO->likeable);
            $likeDTO->itemId = $likeDTO->likeable->id;
            return $likeDTO;
        }

        if (is_null($likeDTO->like)) {
            return $likeDTO;
        }

        $likeDTO->item = class_basename_lower($likeDTO->like->likeable);
        $likeDTO->itemId = $likeDTO->like->likeable->id;
        return $likeDTO;
    }

    private function associateLikeToItem($likeDTO)
    {
        $likeDTO->like->likeable()->associate($likeDTO->likeable);
        $likeDTO->like->save();

        return $likeDTO->like;
    }

    private function makeLike($likeDTO)
    {
        return $likeDTO->likedby->likings()->create([
            'user_id' => $likeDTO->userId
        ]);
    }

    private function setLikedby($likeDTO)
    {
        if ($likeDTO->likedby) {
            return $likeDTO;
        }

        return $likeDTO->withLikedby(
            $this->getModel($likeDTO->account,$likeDTO->accountId)
        );
    }

    private function setlikeable($likeDTO)
    {
        if ($likeDTO->likeable) {
            return $likeDTO;
        }

        if ($likeDTO->like) {
            return $likeDTO->withLikeable($likeDTO->like->likeable);
        }

        return $likeDTO->withLikeable(
            $this->getModel($likeDTO->item,$likeDTO->itemId)
        );
    }

    private function trackSchoolAdmin($likeDTO)
    {
        if (is_null($likeDTO->adminId)) {
            return;
        }

        $admin = $this->getModel('admin',$likeDTO->adminId);

        (new ActivityTrackService())->trackActivity(
            ActivityTrackDTO::createFromData(
                activity: $likeDTO->like,
                activityfor: $likeDTO->like->likedby,
                performedby: $admin,
                action: $likeDTO->method
            )
        );
    }

    private function checkOwnership($likeDTO)
    {
        if ($likeDTO->like->user_id == $likeDTO->userId) {
            return;
        }

        $this->throwLikeException('you cannot unlike a like you do not own.');
    }

    private function broadcastLike($likeDTO)
    {
        $event = $this->getEvent($likeDTO);

        broadcast($event)->toOthers();
    }

    private function getEvent($likeDTO)
    {
        if ($likeDTO->methodType === 'created') {
            return new NewLike($likeDTO);
        }

        return new DeleteLike($likeDTO);
    }

    public function deleteLike(LikeDTO $likeDTO)
    {
        $like = $this->getModel('like',$likeDTO->likeId);

        $likeDTO = $likeDTO->withLike($like);
        
        $this->checkOwnerShip($likeDTO);

        $like->delete();
        
        $likeDTO->method = __METHOD__;
        $this->trackSchoolAdmin($likeDTO);

        $this->setItemForBroadcast($likeDTO);

        $likeDTO->methodType = 'deleted';
        $this->broadcastLike($likeDTO);
    }

    private function throwLikeException($message, $data = null)
    {
        throw new LikeException(
            message: $message,
            data: $data
        );
    }
}