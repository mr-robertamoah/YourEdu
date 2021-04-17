<?php

namespace App\DTOs;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class CommentDTO
{
    public ?Model $commentable = null;
    public ?Model $commentedby = null;
    public ?Model $comment = null;
    public ?string $body = null;
    public ?string $userId = null;
    public ?string $item = null;
    public ?string $itemId = null;
    public ?string $account = null;
    public ?string $accountId = null;
    public ?string $commentId = null;
    public ?string $adminId = null;
    public array $files = [];
    public array $removedFiles = [];
    public ?string $method = null;
    public ?string $methodType = null;

    public static function createFromData
    (
        $method = null,
        $commentedby = null,
        $comment = null,
        $commentable = null,
        $adminId = null,
        $item = null,
        $itemId = null,
        $accountId = null,
        $account = null,
        $body = null,
        $userId = null,
        $commentId = null,
        $files = [],
        $removedFiles = [],
    )
    {
        $self = new static;

        $self->method = $method;
        $self->commentId = $commentId;
        $self->userId = $userId;
        $self->removedFiles = $removedFiles ?: [];
        $self->files = $files ?: [];
        $self->body = $body;
        $self->account = $account;
        $self->accountId = $accountId;
        $self->itemId = $itemId;
        $self->item = $item;
        $self->adminId = $adminId;
        $self->comment = $comment;
        $self->commentedby = $commentedby;
        $self->commentable = $commentable;

        return $self;
    }

    public static function createFromRequest(Request $request)
    {
        $self = new static;

        $self->commentId = $request->commentId;
        $self->account = $request->account;
        $self->accountId = $request->accountId;
        $self->item = $request->item;
        $self->itemId = $request->itemId;
        $self->adminId = $request->adminId;
        $self->userId = $request->user()?->id;
        $self->files = $request->hasFile('file') ?
            [$request->file('file')] : [];
        $self->body = $request->body;

        return $self;
    }

    public function withCommentedby($commentedby)
    {
        $clone = clone $this;

        $clone->commentedby = $commentedby;

        return $clone;
    }

    public function withCommentable($commentable)
    {
        $clone = clone $this;

        $clone->commentable = $commentable;

        return $clone;
    }

    public function withComment($comment)
    {
        $clone = clone $this;

        $clone->comment = $comment;

        return $clone;
    }
}
