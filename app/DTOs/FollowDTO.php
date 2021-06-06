<?php

namespace App\DTOs;

use App\YourEdu\Follow;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class FollowDTO
{
    public ?string $userId = null;
    public ?string $followId = null;
    public ?string $myAccount = null;
    public ?string $myAccountId = null;
    public ?string $otherAccount = null;
    public ?string $otherAccountId = null;
    public ?string $methodType = null;
    public ?Model $followable = null;
    public ?Model $followedby = null;
    public ?Follow $follow = null;

    public static function new()
    {
        return new static;
    }

    public static function createFromRequest(Request $request)
    {
        $self = new static;

        $self->userId = $request->user()?->id;
        $self->followId = $request->followId;
        $self->myAccount = $request->account;
        $self->myAccountId = $request->accountId;
        $self->otherAccount = $request->otherAccount;
        $self->otherAccountId = $request->otherAccountId;
        
        return $self;
    }

    public function withFollowable(Model $followable)
    {
        $clone = clone $this;

        $clone->followable = $followable;

        return $clone;
    }

    public function withFollowedby(Model $followedby)
    {
        $clone = clone $this;

        $clone->followedby = $followedby;

        return $clone;
    }

    public function withFollow(Model $follow)
    {
        $clone = clone $this;

        $clone->follow = $follow;

        return $clone;
    }

}
