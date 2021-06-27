<?php

namespace App\DTOs;

use App\Traits\DTOTrait;
use App\YourEdu\Post;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class PostDTO
{
    use DTOTrait;

    public ?string $content = null;
    public ?string $socketId = null;
    public ?Model $addedby = null;
    public ?string $type = null;
    public ?Model $typeModel = null;
    public ?Post $post = null;
    public $typeDTO = null;
    public ?array $attachments = [];
    public ?array $removedAttachments = [];
    public ?string $postId = null;
    public ?array $files = [];
    public ?array $removedFiles = [];
    public ?string $adminId = null;
    public ?string $account = null;
    public ?string $accountId = null;
    public array $types = ['poem', 'riddle', 'book', 'lesson', 'question', 'activity'];

    public static function createFromRequest(Request $request)
    {
        $self = new static();

        $self->postId = $request->postId;
        $self->socketId = $request->headers->get('x-socket-id');
        $self->content = $request->content;
        $self->account = $request->account;
        $self->accountId = $request->accountId;
        $self->userId = $request->user()->id;
        $self->type = $request->type;
        $self->adminId = $request->adminId;

        $self->attachments = $request->attachments ?
            ModelDTO::createFromArray(
                json_decode($request->attachments)
            ) : [];
        $self->removedAttachments = $request->removedAttachments ?
            ModelDTO::createFromArray(
                json_decode($request->removedAttachments)
            ) : [];
        $self->files = $request->hasFile('file') ?
            $request->file('file') : [];
        $self->removedFiles = $request->removedFiles ?
            FileDTO::createFromArray(
                json_decode($request->removedFiles)
            ) : [];

        if ($self->type === 'book') {
            $self->typeDTO = BookDTO::createFromRequest($request);
        } else if ($self->type === 'riddle') {
            $self->typeDTO = RiddleDTO::createFromRequest($request);
        } else if ($self->type === 'poem') {
            $self->typeDTO = PoemDTO::createFromRequest($request);
        } else if ($self->type === 'activity') {
            $self->typeDTO = ActivityDTO::createFromRequest($request);
        } else if ($self->type === 'question') {
            $self->typeDTO = QuestionDTO::createFromRequest($request);
        } else if ($self->type === 'lesson') {
            $self->typeDTO = LessonDTO::createFromRequest($request);
        }

        return $self;
    }

    public function resetFiles()
    {
        $clone = clone $this;

        $clone->files = [];

        return $clone;
    }
}
