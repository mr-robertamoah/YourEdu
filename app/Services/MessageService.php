<?php

namespace App\Services;

use App\Exceptions\AccountNotFoundException;
use App\Exceptions\MessageException;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class MessageService

{
    public function createMessage($what,$whatId,$account,$accountId,$userId,$message,
        $state,$file,
        $chattingAccount = null,$chattingAccountId = null,$chattingUserId = null)
    {
        $toWhatMessageBelongs = getAccountObject($what,$whatId); //conversation discussion
        if (is_null($toWhatMessageBelongs)) {
            throw new AccountNotFoundException("{$what} does not exist");
        }
        $fromable = getAccountObject($account,$accountId);
        if (is_null($fromable)) {
            throw new AccountNotFoundException("{$account} does not exist");
        }
        $toable = getAccountObject($chattingAccount,$chattingAccountId);
        if ($what === 'conversation' && is_null($toable)) {
            throw new AccountNotFoundException("{$chattingAccount} does not exist");
        }

        $message = $toWhatMessageBelongs->messages()->create([
            'message' => $message,
            'to_user_id' => $chattingUserId,
            'from_user_id' => $userId,
            'state' => Str::upper($state),
        ]);

        if (is_null($message)) {
            throw new MessageException("message creation failed");
        }

        if (!is_null($toable)) {
            $message->toable()->associate($toable);
        }
        $message->fromable()->associate($fromable);
        $message->save();

        if ($file) {
            //fromable has uploaded a file and that file is attached to a message
            if (is_array($file)) {
                foreach ($file as $f) {
                    $this->accountCreateFile($f, $fromable, $message);
                }
            } else {
                $this->accountCreateFile($file, $fromable, $message);
            }
        }

        return $message->load('images','videos','audios','files','flags','fromable.profile');
    }

    private function accountCreateFile($file, $account, $item)
    {
        $fileDetails = getFileDetails($file);
    
        $uploadedFile = accountCreateFile($account,$fileDetails,$item);
        $uploadedFile->ownedby()->associate($account);
        $uploadedFile->save();
    }

    public function deleteMessage($userId, $messageId, $action)
    {
        $message = getAccountObject('message', $messageId);

        if (is_null($message)) {
            throw new AccountNotFoundException("message not found with id {$messageId}");
        }

        if ($action === 'self') {
            $message->timestamps = false;
            if (is_null($message->user_deletes)) {
                $message->user_deletes = [$userId];
            } else {
                $message->user_deletes = Arr::prepend($message->user_deletes, $userId);
            }
            $message->save();
            return $message->load('images','videos','audios','files','flags','fromable.profile');
        } else if ($action === 'delete') {
            if ($message->from_user_id !== $userId) {
                throw new MessageException("you are not authorized to delete this message");
            }

            deleteYourEduFiles($message);
            $message->setTouchedRelations([]);
            $message->timestamps = false;
            $message->delete();
            return [
                'item' => 'message',
                'itemId' => $messageId
            ];
        }
    }
}