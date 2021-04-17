<?php

namespace App\DTOs;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class LikeDTO
{
    public ?Model $likeable = null;
    public ?Model $likedby = null;
    public ?Model $like = null;
    public ?string $userId = null;
    public ?string $item = null;
    public ?string $itemId = null;
    public ?string $account = null;
    public ?string $accountId = null;
    public ?string $likeId = null;
    public ?string $adminId = null;
    public ?string $method = null;
    public ?string $methodType = null;

    public static function createFromRequest(Request $request)
    {
        $self = new static;

        $self->likeId = $request->likeId;
        $self->account = $request->account;
        $self->accountId = $request->accountId;
        $self->item = $request->item;
        $self->itemId = $request->itemId;
        $self->adminId = $request->adminId;
        $self->userId = $request->user()?->id;

        return $self;
    }

    public function withLikedby($likedby)
    {
        $clone = clone $this;

        $clone->likedby = $likedby;

        return $clone;
    }

    public function withLikeable($likeable)
    {
        $clone = clone $this;

        $clone->likeable = $likeable;

        return $clone;
    }

    public function withLike($like)
    {
        $clone = clone $this;

        $clone->like = $like;

        return $clone;
    }
}
