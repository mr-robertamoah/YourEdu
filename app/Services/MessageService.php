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

    const PAGINATION = 5;

    const MAX_NUMBER_OF_FILES = 3;
    
    public function createMessage(MessageDTO $messageDTO)
    {
        $messageDTO = $this->setMessageable($messageDTO);

        $messageDTO = $this->setFromable($messageDTO);

        $messageDTO = $this->setToable($messageDTO);

        $message = $this->addMessage($messageDTO);

        $this->checkMessage($message);

        $this->checkFiles($messageDTO);

        $message = $this->associateMessageToFromable($message, $messageDTO);

        $message = $this->associateMessageToToable($message, $messageDTO);

        $messageDTO = $messageDTO->withMessage($message);

        $message = $this->addFiles($messageDTO);

        return $this->getReturnedMessage($message);
    }

    private function checkFiles($messageDTO)
    {
        $this->checkNumberOfFiles($messageDTO);

        // $this->checkTypeOfFiles($messageDTO);

        // $this->checkDurationOfFiles($messageDTO);

        // $this->checkSizeOfFiles($messageDTO);
    }

    private function checkNumberOfFiles($messageDTO)
    {
        $itemFilesDTO = (new FileService)->countPossibleItemFiles(
            $messageDTO->message,
            $messageDTO
        );
        
        if ($itemFilesDTO->totalFiles() <= self::MAX_NUMBER_OF_FILES) {
            return;
        }

        $maxFiles = self::MAX_NUMBER_OF_FILES;
        
        $this->throwMessageException(
            message: "sorry 😞, you cannot have more than {$maxFiles} files for a message.",
            data: $messageDTO
        );
    }

    private function getReturnedMessage(Message $message)
    {
        if (class_basename_lower($message->messageable) === 'request') {
            return $message;
        }

        return $this->withLoadedMessageRelationships($message);
    }

    private function withLoadedMessageRelationships($message)
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
            'user_deletes' => [],
            'user_seens' => [],
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

    private function setFromableFromMessage($messageDTO) : MessageDTO
    {
        if (! $messageDTO->requireAuthorization) {
            return $messageDTO;
        }

        if ($messageDTO->fromable) {
            return $messageDTO;
        }
        
        if ($messageDTO->message->fromable->isUser($messageDTO->userId)) {
            return $messageDTO->withFromable($messageDTO->message->fromable);
        }

        return $messageDTO->withFromable($messageDTO->message->toable);
    }

    public function deleteMessage(MessageDTO $messageDTO)
    {
        $message = $this->getMessage($messageDTO);

        $messageDTO = $this->setFromableFromMessage($messageDTO);
        
        $this->checkAuthorization($messageDTO);
        
        $messageDTO = $messageDTO->withMessage($message);

        if ($messageDTO->action === 'self') {
            return $this->deleteMessageForSelf($messageDTO);
        }

        if ($messageDTO->action === 'all') {
            return $this->deleteMessageForAll($messageDTO);
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
        $messageDTO->message->setTouchedRelations([]);
        $messageDTO->message->timestamps = false;
        
        $messageDTO->message->user_deletes = Arr::prepend(
            $messageDTO->message->user_deletes, 
            $messageDTO->fromUserId
        );

        $messageDTO->message->save();

        return $this->withLoadedMessageRelationships($messageDTO->message);
    }

    private function deleteMessageForAll(MessageDTO $messageDTO)
    {
        $messageDTO->message->setTouchedRelations([]);
        $messageDTO->message->timestamps = false;
        
        $messageDTO->message->state = "DELETED";

        $messageDTO->message->save();

        return $this->withLoadedMessageRelationships($messageDTO->message);
    }

    private function deleteFiles(MessageDTO $messageDTO)
    {
        (new FileService)->deleteYourEduItemFiles($messageDTO->message);
    }

    private function getMessageable($messageDTO)
    {
        if (is_not_null($messageDTO->messageable)) {
            return $messageDTO->messageable;
        }

        return $this->getModel($messageDTO->item,$messageDTO->itemId);
    }

    public function getMessagesBasedOnItem(MessageDTO $messageDTO)
    {
        $messageable = $this->getMessageable($messageDTO);

        return $messageable->messages()->orderBy($messageDTO->orderBy)->paginate(
            self::PAGINATION
        );
    }

    public function updateMessageState(MessageDTO $messageDTO)
    {
        $message = $this->getMessage($messageDTO);

        $message->setTouchedRelations([]);
        $message->timestamps = false;

        if ($messageDTO->state === 'seen') {
            $message = $this->updateMessageSeenState($messageDTO);
        }

        $message->state = strtoupper($messageDTO->state);
        $message->save();
        
        return $message;
    }

    public function updateMessageSeenState($messageDTO)
    {
        $message = $this->getMessage($messageDTO);

        if ($message->isSeenBy($messageDTO->fromUserId)) {
            return;
        }

        $message->user_seens = Arr::prepend($message->user_seens, $messageDTO->fromUserId);
        $message->save();
        
        return $message;
    }
}