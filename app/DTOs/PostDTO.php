<?php

namespace App\DTOs;

use App\Contracts\PostTypeDTOContract;
use App\YourEdu\Post;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class PostDTO 
{
    public string | null $content;
    public ?string $socketId = null;
    public ?Model $addedby = null;
    public string | null $type;
    public ?Model $typeModel = null;
    public ?Post $post = null;
    public ?PostTypeDTOContract $typeDTO = null;
    public array | null $attachments;
    public array | null $removedAttachments;
    public string | null $postId;
    public array | null $files;
    public array | null $removedFiles;
    public string | null $adminId;
    public string | null $account;
    public string | null $accountId;
    public int | null $userId;
    public array $types = ['poem', 'riddle','book', 'lesson', 'question', 'activity'];

    public static function createFromRequest(Request $request)
    {
        $self = new static();

        $self->postId = $request->postId;
        $self->socketId = $request->headers->get('x-socket-id');
        $self->content = $request->content;
        $self->account = $request->account;
        $self->accountId = $request->accountId;
        $self->userId = (int) $request->user()->id;
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

    public function withPost(Post $post)
    {
        $clone = clone $this;

        $clone->post = $post;

        return $clone;
    }

    public function resetFiles()
    {
        $clone = clone $this;

        $clone->files = [];

        return $clone;
    }
}
