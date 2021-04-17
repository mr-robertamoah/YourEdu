<?php

namespace App\Services;

use App\DTOs\MessageDTO;
use App\Exceptions\AccountNotFoundException;
use App\Exceptions\MessageException;
use App\Traits\ServiceTrait;
use App\YourEdu\Message;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class MessageService

{
    use ServiceTrait;
    
    public function createMessage(MessageDTO $messageDTO)
    {
        $messageDTO = $this->setMessageable($messageDTO);

        $messageDTO = $this->setFromable($messageDTO);

        $messageDTO = $this->setToable($messageDTO);

        $message = $this->addMessage($messageDTO);

        $this->checkMessage($message);

        $message = $this->associateMessageToFromable($message, $messageDTO);

        $message = $this->associateMessageToToable($message, $messageDTO);

        $messageDTO = $messageDTO->withMessage($message);

        $message = $this->addFiles($messageDTO);

        return $this->getReturnedMessage($message);
    }

    private function getReturnedMessage(Message $message)
    {
        if (class_basename_lower($message->messageable) === 'request') {
            return $message;
        }

        return $this->getMessageWithLoadedData($message);
    }

    private function getMessageWithLoadedData($message)
    {
        return $message->load('images','videos','audios','files','flags','fromable.profile');
    }

    private function associateMessageToFromable($message, $messageDTO)
    {
        if (is_null($messageDTO->fromable)) {
            return $message;
        }

        $message->fromable()->associate($messageDTO->fromable);
        $message->save();

        return $message;
    }

    private function associateMessageToToable($message, $messageDTO)
    {
        if (is_null($messageDTO->toable)) {
            return $message;
        }
        
        $message->toable()->associate($messageDTO->toable);
        $message->save();

        return $message;
    }

    private function setMessageable(MessageDTO $messageDTO)
    {
        if (is_not_null($messageDTO->messageable)) {
            return $messageDTO;
        }

        return $messageDTO->withMessageable(
            $this->getModel($messageDTO->item,$messageDTO->itemId)
        );
    }

    private function setFromable(MessageDTO $messageDTO)
    {
        if (is_not_null($messageDTO->fromable)) {
            return $messageDTO;
        }
        
        return $messageDTO->withFromable(
            $this->getModel($messageDTO->fromAccount,$messageDTO->fromAccountId)
        );
    }

    private function doesntRequireToable(MessageDTO $messageDTO)
    {
        if ($messageDTO->item === 'discussion') {
            return true;
        }

        return false;
    }

    private function setToable(MessageDTO $messageDTO)
    {
        if ($this->doesntRequireToable($messageDTO)) {
            return $messageDTO;
        }

        if (is_not_null($messageDTO->toable)) {
            return $messageDTO;
        }
        
        return $messageDTO->withToable(
            $this->getModel($messageDTO->toAccount,$messageDTO->toAccountId)
        );
    }

    private function checkMessage($message)
    {
        if (is_not_null($message)) {
            return;
        }
        
        throw new MessageException("message creation failed");
    }

    private function addFiles(MessageDTO $messageDTO)
    {
        foreach ($messageDTO->files as $file) {
            $this->accountCreateFile($file, $messageDTO);
        }

        return $messageDTO->message->refresh();
    }

    private function addMessage(MessageDTO $messageDTO)
    {
        return $messageDTO->messageable->messages()->create([
            'message' => $messageDTO->messageText,
            'to_user_id' => $messageDTO->toUserId,
            'from_user_id' => $messageDTO->fromUserId,
            'state' => Str::upper($messageDTO->state ?: 'sent'),
        ]);
    }

    private function throwMessageException($message, $data = null)
    {
        throw new MessageException(
            message: $message,
            data: $data
        );
    }

    private function accountCreateFile($file, $messageDTO)
    {
        $uploadedFile = FileService::createAndAttachFiles(
            account: $messageDTO->fromable,
            file: $file,
            item: $messageDTO->message
        );

        $uploadedFile->ownedby()->associate($messageDTO->fromable);
        $uploadedFile->save();
    }

    private function checkAuthorization(MessageDTO $messageDTO)
    {
        if (! $messageDTO->requireAuthorization) {
            return;
        }

        if ($messageDTO->fromable->isAuthorizedUserById($messageDTO->fromUserId)) {
            return;
        }

        $this->throwMessageException(
            message: "you are not authorized to delete this message",
            data: $messageDTO
        );
    }

    public function deleteMessage(MessageDTO $messageDTO)
    {
        $message = $this->getMessage($messageDTO);

        $messageDTO = $messageDTO->withFromable($message->fromable);
        
        $this->checkAuthorization($messageDTO);
        
        $messageDTO = $messageDTO->withMessage($message);

        if ($messageDTO->action === 'self') {
            return $this->deleteMessageForSelf($messageDTO);
        }

        $this->deleteFiles($messageDTO);

        $message->setTouchedRelations([]);
        $message->timestamps = false;

        $message->delete();
    }

    private function getMessage($messageDTO)
    {
        if ($messageDTO->message) {
            return $messageDTO->message;
        }

        return $this->getModel('message', $messageDTO->messageId);
    }

    private function deleteMessageForSelf(MessageDTO $messageDTO)
    {
        $messageDTO->message->timestamps = false;
        if (is_null($messageDTO->message->user_deletes)) {
            $messageDTO->message->user_deletes = [];
        }
        
        $messageDTO->message->user_deletes = Arr::prepend(
            $messageDTO->message->user_deletes, 
            $messageDTO->fromUserId
        );

        $messageDTO->message->save();

        return $this->getMessageWithLoadedData($messageDTO->message);
    }

    private function deleteFiles(MessageDTO $messageDTO)
    {
        (new FileService)->deleteYourEduItemFiles($messageDTO->message);
    }
}