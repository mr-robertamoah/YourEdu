<?php

namespace App\Services;

use App\Exceptions\AccountNotFoundException;
use App\Exceptions\CommentException;
use Illuminate\Support\Facades\Storage;

class CommentService
{
    public function commentCreate($body,$file,$account,$accountId,$id,$item,$itemId)
    {
        if(is_null($body) && is_null($file)){
            throw new CommentException('unsuccessful. there is nothing to add as comment.');
        }
        
        $mainAccount = getAccountObject($account,$accountId);
        if (is_null($mainAccount)) {
            throw new AccountNotFoundException("unsuccessful. there is no such {$account}.");
        }

        $this->checkAccountOwnership($mainAccount,$id);        

        $commentable = getAccountObject($item,$itemId);
        if (is_null($commentable)) {
            throw new AccountNotFoundException("unsuccessful. there is no such {$account}.");
        }

        $comment = $mainAccount->comments()->create([
            'body' => $body
        ]);
        
        if (!is_null($file)) {
            $fileDetails = getFileDetails($file);

            $file = accountCreateFile(
                $mainAccount, 
                $fileDetails,
                $comment
            );
        }

        $commentData = $this->commentAssociate($comment,$commentable,$item);    
            
        if ($commentData['rollback']) {
            if($file){
                Storage::delete($file->path);
            }
            throw new CommentException("unsuccessful. {$item} does not exist or comment was not created");
        }

        return $commentData;
    }

    private function checkAccountOwnership($account,$id)
    {
        if ($account->user_id && $account->user_id !== $id) {
            throw new CommentException("unsuccessful. you do not own this account.");
        } else if ($account->owner_id && $account->owner_id !== $id) {
            throw new CommentException("unsuccessful. you do not own this account.");
        }
    }
    
    public function commentEdit($account,$accountId,$id,$commentId,$body)
    {
        // for now, body must be required, on a later date, it wont be. but we will 
        //check to ensure that the update doesnt lead to an empty comment (without body and file)

        $mainAccount = getAccountObject($account,$accountId);
        if (is_null($mainAccount)) {
            throw new AccountNotFoundException("unsuccessful. there is no such {$account}.");
        }

        $this->checkAccountOwnership($mainAccount,$id);

        $comment = $mainAccount->comments()->where('id', $commentId)->first();
            
        if (is_null($comment)) {
            throw new CommentException("unsuccessful. there is no such comment for this accout user.");
        }
        
        $comment->update([
            'body' => $body
        ]);

        return [
            'comment' => $comment,
        ];
    }
    
    public function commentDelete($commentId)
    {
        $comment = getAccountObject('comment',$commentId);
        if (is_null($comment)) {
            throw new AccountNotFoundException("comment not found with id {$commentId}.");
        }

        $item = getAccountString($comment->commentable_type);
        $itemId = $comment->commentable_id;
        $account = null;
        $accountId = null;
        if ($item === 'post') {
            $account = getAccountString(get_class($comment->commentable->postedby));
            $accountId = $comment->commentable->postedby->id;
        } else if ($item === 'book' || $item === 'poem' || $item === 'activity') {
            $account = getAccountString(get_class($comment->commentable->post->postedby));
            $accountId = $comment->commentable->post->postedby->id;
        } else if ($item === 'discussion') {
            $account = getAccountString(get_class($comment->commentable->raisedby));
            $accountId = $comment->commentable->raisedby->id;
        }
        $comment->delete();

        return [
            'item' => $item,
            'itemId' => $itemId,
            'account' => $account,
            'accountId' => $accountId,
        ];
    }
    
    public function commentsGet($item, $itemId)
    {
        $commentable = getAccountObject($item,$itemId);
        if (is_null($commentable)) {
            throw new AccountNotFoundException("{$item} not found with id {$itemId}.");
        }
        
        return $commentable->comments()->latest()->get();
    }

    private function commentAssociate($comment,$commentable, $item)
    {
        $commentableOwner = null;
        $rollback = false;
        if ($item === 'post') {
            $commentableOwner = $commentable->postedby;
        } else if ($item === 'discussion') {
            $commentableOwner = $commentable->raisedby;
        } else if ($item === 'activity') {
            $commentableOwner = $commentable->post->postedby;
        } else if ($item === 'book') {
            $commentableOwner = $commentable->post->postedby;
        } else if ($item === 'riddle') {
            $commentableOwner = $commentable->post->postedby;
        } else if ($item === 'question') {
            $commentableOwner = $commentable->post->postedby;
        } else if ($item === 'poem') {
            $commentableOwner = $commentable->post->postedby;
        } else if ($item === 'comment') {

        } else if ($item === 'lesson') {

        } else if ($item === 'class') {

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

        }

        if ($comment && $commentable) {
            $comment->commentable()->associate($commentable);
            $comment->save();
        } else {
            $rollback = true;
        }
        return [
            'rollback' => $rollback,
            'comment' => $comment,
            'commentableOwner' => $commentableOwner,
        ];
    }
}