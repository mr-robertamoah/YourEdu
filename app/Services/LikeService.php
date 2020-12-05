<?php

namespace App\Services;

use App\Exceptions\AccountNotFoundException;
use App\Exceptions\LikeException;

class LikeService
{
    public function likeCreate($account,$accountId,$item,$itemId,$id,$adminId)
    {
        $mainAccount = getAccountObject($account,$accountId);
        if (is_null($mainAccount)) {
            throw new AccountNotFoundException("{$account} not found with id {$accountId}");
        }

        $mainItem = getAccountObject($item,$itemId);
        if (is_null($mainItem)) {
            throw new AccountNotFoundException("{$item} not found with id {$itemId}");
        }

        $like = $mainAccount->likings()->create([
            'user_id' => $id
        ]);
        $like->likeable()->associate($mainItem);
        $like->save();
        
        if ($adminId) {
            $admin = getAccountObject('admin',$adminId);
            if (!is_null($admin)) {
                (new ActivityTrackService())->createActivityTrack(
                    $like,$like->likedby,$admin,__METHOD__
                );
            }
        }

        $itemInfo = $this->getItemInfo($mainItem, $item);
        return [
            'like' => $like,
            'itemBelongsTo' => $itemInfo['itemBelongsTo'],
            'itemBelongsToId' => $itemInfo['itemBelongsToId'],
        ];
    }

    private function getItemInfo($mainItem,$item)
    {
        $itemBelongsTo = null;
        $itemBelongsToId = null;
        if ($item === 'comment') {
            $itemBelongsToId = $mainItem->commentable_id;
            $itemBelongsTo = getAccountString($mainItem->commentable_type);
        } else if ($item === 'answer') {
            $itemBelongsToId = $mainItem->answerable_id;
            $itemBelongsTo = getAccountString($mainItem->answerable_type);
        }

        return [
            'itemBelongsTo' => $itemBelongsTo,
            'itemBelongsToId' => $itemBelongsToId,
        ];
    }

    public function likeDelete($likeId,$id,$adminId)
    {
        $like = getAccountObject('like',$likeId);
        if (is_null($like)) {
            throw new AccountNotFoundException("like not found with id {$likeId}");
        }
        if ($like->user_id !== $id) {
            throw new LikeException('you cannot unlike a like you do not own.');
        }

        $item = getAccountString($like->likeable_type);
        $itemId = $like->likeable_id;

        $itemInfo = $this->getItemInfo($like->likeable, $item);
        
        if ($adminId) {
            $admin = getAccountObject('admin',$adminId);
            if (!is_null($admin)) {
                (new ActivityTrackService())->createActivityTrack(
                    $like,$like->likedby,$admin,__METHOD__
                );
            }
        }
        $like->delete();

        return [
            'item' => $item,
            'itemId' => $itemId,
            'itemBelongsTo' => $itemInfo['itemBelongsTo'],
            'itemBelongsToId' => $itemInfo['itemBelongsToId'],
        ];
    }
}