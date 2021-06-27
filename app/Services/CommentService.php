<?php

namespace App\Services;

use App\DTOs\ActivityTrackDTO;
use App\DTOs\CommentDTO;
use App\Events\DeleteComment;
use App\Events\NewComment;
use App\Events\UpdateComment;
use App\Exceptions\CommentException;
use App\Traits\ServiceTrait;

class CommentService
{
    use ServiceTrait;

    public function createComment(CommentDTO $commentDTO)
    {
        ray($commentDTO)->green();
        $this->checkCommentData($commentDTO);

        $commentDTO = $this->setCommentedby($commentDTO);

        $this->checkAccountOwnership($commentDTO);

        $commentDTO = $this->setCommentable($commentDTO);

        $comment = $this->makeComment($commentDTO);

        $this->checkComment($comment);

        $commentDTO = $commentDTO->withComment($comment);

        $comment = $this->associateCommentToItem($commentDTO);

        $comment = $this->addFiles($commentDTO);

        $commentDTO->method = __METHOD__;
        $this->trackSchoolAdmin($commentDTO);

        $commentDTO = $this->setAccountAndItemForBroadcast($commentDTO);

        $commentDTO->methodType = 'created';
        $this->broadcastComment($commentDTO);

        return $comment->refresh();
    }

    private function broadcastComment($commentDTO)
    {
        $event = $this->getEvent($commentDTO);

        broadcast($event)->toOthers();
    }

    private function getEvent($commentDTO)
    {
        if ($commentDTO->methodType === 'created') {
            return new NewComment($commentDTO);
        }

        if ($commentDTO->methodType === 'updated') {
            return new UpdateComment($commentDTO);
        }

        return new DeleteComment($commentDTO);
    }

    private function addFiles(CommentDTO $commentDTO)
    {
        foreach ($commentDTO->files as $file) {
            FileService::createAndAttachFiles(
                account: $commentDTO->commentedby,
                file: $file,
                item: $commentDTO->comment
            );
        }

        return $commentDTO->comment->refresh();
    }

    private function makeComment(CommentDTO $commentDTO)
    {
        $method = $this->getAccountCommentingMethod($commentDTO);

        return $commentDTO->commentedby->$method()->create([
            'body' => $commentDTO->body
        ]);
    }

    private function setCommentedby($commentDTO)
    {
        if ($commentDTO->commentedby) {
            return $commentDTO;
        }

        return $commentDTO->withCommentedby(
            $this->getModel($commentDTO->account, $commentDTO->accountId)
        );
    }

    private function setCommentable($commentDTO)
    {
        if ($commentDTO->commentable) {
            return $commentDTO;
        }

        if ($commentDTO->comment) {
            return $commentDTO->withCommentable($commentDTO->comment->commentable);
        }

        return $commentDTO->withCommentable(
            $this->getModel($commentDTO->item, $commentDTO->itemId)
        );
    }

    private function checkCommentData($commentDTO)
    {
        if (is_not_null($commentDTO->body) || count($commentDTO->files)) {
            return;
        }

        $this->throwCommentException('unsuccessful. there is nothing to add as comment.');
    }

    private function throwCommentException($message, $data = null)
    {
        throw new CommentException(
            message: $message,
            data: $data
        );
    }

    private function trackSchoolAdmin(CommentDTO $commentDTO)
    {
        if (is_null($commentDTO->adminId)) {
            return;
        }

        $admin = $this->getModel('admin', $commentDTO->adminId);

        (new ActivityTrackService)->trackActivity(
            ActivityTrackDTO::createFromData(
                activity: $commentDTO->comment,
                activityfor: $commentDTO->commentedby,
                performedby: $admin,
                action: $commentDTO->method
            )
        );
    }

    private function checkAccountOwnership($commentDTO)
    {
        if (in_array($commentDTO->userId, $commentDTO->commentedby->getAuthorizedIds())) {
            return;
        }

        $this->throwCommentException("unsuccessful. you are not authorised to perform this action using this account.");
    }

    private function checkComment($comment)
    {
        if (is_not_null($comment)) {
            return;
        }

        $this->throwCommentException("failed to create or find the comment.");
    }

    public function updateComment(CommentDTO $commentDTO)
    {
        $this->checkCommentData($commentDTO);

        $commentDTO = $this->setCommentedby($commentDTO);

        $this->checkAccountOwnership($commentDTO);

        $comment = $this->editComment($commentDTO);

        $this->checkComment($comment);

        $commentDTO = $commentDTO->withComment($comment);

        $commentDTO = $this->setCommentable($commentDTO);

        $comment = $this->addFiles($commentDTO);

        $commentDTO->method = __METHOD__;
        $this->trackSchoolAdmin($commentDTO);

        $commentDTO = $this->setAccountAndItemForBroadcast($commentDTO);

        $commentDTO->methodType = 'updated';
        $this->broadcastComment($commentDTO);

        return $comment;
    }

    private function getAccountCommentingMethod($commentDTO): string
    {
        if ($commentDTO->commentedby->accountType === 'school') {
            return 'commentsMade';
        }

        return 'comments';
    }

    private function editComment($commentDTO)
    {
        $method = $this->getAccountCommentingMethod($commentDTO);

        $comment = $commentDTO->commentedby
            ->$method()->where('id', $commentDTO->commentId)->first();

        $comment->update([
            'body' => $commentDTO->body
        ]);

        return $comment;
    }

    public function deleteComment(CommentDTO $commentDTO)
    {
        $comment = $this->getModel('comment', $commentDTO->commentId);

        $commentDTO = $commentDTO->withComment($comment);

        $commentDTO = $this->setAccountAndItemForBroadcast($commentDTO);

        $comment->delete();

        $commentDTO->method = __METHOD__;
        $this->trackSchoolAdmin($commentDTO);

        $commentDTO->methodType = 'deleted';
        $this->broadcastComment($commentDTO);

        $this->deleteFiles($commentDTO);
    }

    private function deleteFiles($commentDTO)
    {
        FileService::deleteYourEduItemFiles($commentDTO->comment);
    }

    private function setAccountAndItemForBroadcast($commentDTO)
    {
        $commentDTO->item = class_basename_lower(
            $commentDTO->comment->commentable
        );
        $commentDTO->itemId = $commentDTO->comment->commentable->id;

        $commentableOwnerAccount = $this->getCommentableOwnerAccount(
            $commentDTO->comment
        );

        $commentDTO->account = $commentableOwnerAccount->accountType;
        $commentDTO->accountId = $commentableOwnerAccount->id;

        return $commentDTO;
    }

    public function getComments(CommentDTO $commentDTO)
    {
        $commentable = $this->getModel($commentDTO->item, $commentDTO->itemId);

        return $commentable->comments()->latest()->get();
    }

    private function associateCommentToItem($commentDTO)
    {
        $commentDTO->comment->commentable()->associate($commentDTO->commentable);
        $commentDTO->comment->save();

        return $commentDTO->comment;
    }

    public function getCommentableOwnerAccount($comment)
    {
        $item = class_basename_lower($comment->commentable);

        if ($item === 'post') {
            return $comment->commentable->addedby;
        } else if ($item === 'discussion') {
            return $comment->commentable->raisedby;
        } else if ($item === 'activity') {
            return $comment->commentable->post->addedby;
        } else if ($item === 'book') {
            return $comment->commentable->post->addedby;
        } else if ($item === 'riddle') {
            return $comment->commentable->post->addedby;
        } else if ($item === 'question') {
            return $comment->commentable->post->addedby;
        } else if ($item === 'poem') {
            return $comment->commentable->post->addedby;
        } else if ($item === 'comment') {
            return $comment->commentable->commentedby;
        } else if ($item === 'request') {
        } else if ($item === 'admission') {
        } else if ($item === 'ban') {
        } else if ($item === 'flag') {
        } else if ($item === 'answer') {
        } else if ($item === 'keyword') {
        } else if ($item === 'word') {
        } else if ($item === 'expression') {
        } else if ($item === 'character') {
        } else if ($item === 'school') {
        } else if (
            $item === 'class' || $item === 'course' ||
            $item === 'extracurriculum' || $item === 'lesson'
        ) {
            return $comment->commentable->ownedby;
        }

        return null;
    }
}
