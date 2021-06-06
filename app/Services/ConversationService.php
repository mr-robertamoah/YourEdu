<?php

namespace App\Services;

use App\DTOs\AnswerDTO;
use App\DTOs\ConversationDTO;
use App\DTOs\MessageDTO;
use App\DTOs\QuestionDTO;
use App\Events\ConversationResponse;
use App\Events\DeleteChatMessage;
use App\Events\NewChatAnswer;
use App\Events\NewChatMark;
use App\Events\NewChatMessage;
use App\Events\NewConversation;
use App\Events\UpdateChatMessage;
use App\Exceptions\ConversationException;
use App\Traits\ServiceTrait;
use App\YourEdu\Conversation;
use App\YourEdu\ConversationAccount;
use App\YourEdu\Follow;
use App\YourEdu\Message;

class ConversationService
{
    use ServiceTrait;

    const PAGINATION = 10;

    public function createConversation(ConversationDTO $conversationDTO)
    {
        $conversationDTO = $this->setMyChattingAccount($conversationDTO);

        $conversationDTO = $this->setOtherChattingAccount($conversationDTO);

        $conversation = $this->getConversationUsingAccounts($conversationDTO);

        $conversationDTO->methodType = 'updated';

        if (is_null($conversation)) {

            $conversation = $this->makeConversation($conversationDTO);

            $conversationDTO = $this->setConversation($conversationDTO, $conversation);

            $conversation = $this->addMyAccountToConversation($conversationDTO);

            $conversation = $this->addOtherAccountToConversation($conversationDTO);
            
            $conversationDTO->methodType = 'created';
        }

        $conversationDTO = $this->setConversation($conversationDTO, $conversation);

        $this->updateConversationAccountsFollows($conversationDTO);
        
        $this->broadcastConversation($conversationDTO);

        return $this->withLoadedConversationRelationship($conversation); 
    }

    private function checkResponse($conversationDTO)
    {
        if (in_array($conversationDTO->response, ['accept', 'decline', 'block'])) {
            return;
        }

        $this->throwConversationException(
            message: "sorry 😞, {$conversationDTO->response} is not a correct response",
            data: $conversationDTO
        );
    }

    public function createConversationResponse(ConversationDTO $conversationDTO)
    {
        $this->checkResponse($conversationDTO);

        $conversationDTO = $this->setMyChattingAccount($conversationDTO);

        $this->checkAccountOwnership($conversationDTO->myChattingAccount, $conversationDTO->userId);

        $conversationDTO = $this->setOtherChattingAccount($conversationDTO);

        $conversationDTO = $this->setConversation($conversationDTO);

        $conversationAccount = $this->getConversationAccountUsingUserId($conversationDTO);

        $this->updateConversationAccountState($conversationAccount, $conversationDTO->response);

        $this->updateConversationAccountsFollows($conversationDTO, $conversationDTO->response);
        
        $conversationDTO->methodType = $conversationDTO->response;
        $this->broadcastConversation($conversationDTO);
        
        return $this->withLoadedConversationRelationship($conversationDTO->conversation->refresh());
    }

    private function checkMyAccountsAccessToConversation(ConversationDTO $conversationDTO)
    {
        if ($conversationDTO->conversation->hasSpecificConversationAccount($conversationDTO->myChattingAccount)) {
            return;
        }

        $this->throwConversationException(
            message: "sorry 😞, your account {$conversationDTO->myChattingAccount->accountType} does not have access to conversation.",
            data: $conversationDTO
        );
    }

    private function setConversation($conversationDTO, $conversation = null)
    {
        if (is_not_null($conversationDTO->conversation)) {
            return $conversationDTO;
        }
        
        if (is_not_null($conversation)) {
            return $conversationDTO->withConversation($conversation);
        }

        return $conversationDTO->withConversation(
            $this->getModel('conversation', $conversationDTO->conversationId)
        );
    }

    private function addMyAccountToConversation
    (
        ConversationDTO $conversationDTO
    ) : Conversation
    {
        $this->addAccountToConversation($conversationDTO, ConversationAccount::REQUEST);
        
        return $conversationDTO->conversation->refresh();
    }

    private function addOtherAccountToConversation
    (
        ConversationDTO $conversationDTO
    ) : Conversation
    {
        $this->addAccountToConversation($conversationDTO, ConversationAccount::PENDING);
        
        return $conversationDTO->conversation->refresh();
    }

    private function addAccountToConversation
    (
        ConversationDTO $conversationDTO, $state
    )
    {
        $account = $state === 'REQUEST' ? 
            $conversationDTO->myChattingAccount: 
            $conversationDTO->otherChattingAccount;

        $conversationAccount = $conversationDTO->conversation->conversationAccounts()->create([
            'user_id' => $conversationDTO->userId,
            'state' => $state
        ]);

        $conversationAccount->accountable()->associate($account);
        $conversationAccount->save();
    }

    private function makeConversation($conversationDTO)
    {
        $conversation = Conversation::create([
            'type' => 'PRIVATE',
            'state' => 'CLOSED',
            'name' => $conversationDTO->name,
            'description' => $conversationDTO->description,
            'account_type' => $conversationDTO->accountType
        ]);

        if (is_not_null($conversation)) {
            return $conversation;
        }

        $this->throwConversationException(
            message: 'sorry 😞, could not create converation',
            data: $conversationDTO
        );
    }

    private function getConversationUsingAccounts(ConversationDTO $conversationDTO)
    {
        return Conversation::involvingBothAccounts(
            $conversationDTO->myChattingAccount,
            $conversationDTO->otherChattingAccount,
        );
    }

    public function unblockConversation(ConversationDTO $conversationDTO)
    {
        $conversationDTO->response = 'accept';

        return $this->createConversationResponse($conversationDTO);
    }

    public function blockConversation(ConversationDTO $conversationDTO)
    {
        $conversationDTO->response = 'block';

        return $this->createConversationResponse($conversationDTO);
    }

    private function blockConversationAccount($conversationDTO)
    {
        $conversationAccount = $this->getConversationAccountUsingUserId($conversationDTO);
        
        $conversationAccount = $this->updateConversationAccountState(
            $conversationAccount, ConversationAccount::BLOCK
        );

        return $conversationDTO;
    }

    private function unblockConversationAccount($conversationDTO)
    {
        $conversationAccount = $this->getConversationAccountUsingUserId($conversationDTO);
        
        $conversationAccount = $this->updateConversationAccountState(
            $conversationAccount, ConversationAccount::ACCEPT
        );

        return $conversationDTO;
    }

    private function updateConversationAccountState
    (
        ConversationAccount $conversationAccount,
        string $state
    )
    {
        $conversationAccount->state = strtoupper($state);
        $conversationAccount->save();

        return $conversationAccount;
    }

    private function getConversationAccountUsingUserId(ConversationDTO $conversationDTO)
    {
        $conversationAccount =  $conversationDTO->conversation->conversationAccounts()
            ->whereHasMorph('accountable', '*',function($query) use ($conversationDTO) {
                $query->whereUser($conversationDTO->userId);
            })->first();

        if (is_not_null($conversationAccount)) {
            return $conversationAccount;
        }

        $this->throwConversationException(
            message: 'sorry 😞, your account does not have access to this conversation.',
            data: $conversationDTO
        );
    }

    private function throwConversationException($message, $data = null)
    {
        throw new ConversationException(
            message: $message, 
            data: $data
        );
    }

    private function setMyChattingAccount(ConversationDTO $conversationDTO)
    {
        if (is_not_null($conversationDTO->myChattingAccount)) {
            return $conversationDTO;
        }

        return $conversationDTO->withMyChattingAccount(
            $this->getModel($conversationDTO->myAccount, $conversationDTO->myAccountId)
        );
    }

    private function setOtherChattingAccount(ConversationDTO $conversationDTO)
    {
        if (is_not_null($conversationDTO->otherChattingAccount)) {
            return $conversationDTO;
        }

        return $conversationDTO->withOtherChattingAccount(
            $this->getModel($conversationDTO->otherAccount, $conversationDTO->otherAccountId)
        );
    }

    private function getConversationFollowsUsingAccounts($conversationDTO)
    {
        return Follow::involvingBothAccounts(
            $conversationDTO->myChattingAccount,
            $conversationDTO->otherChattingAccount,
        );
    }

    private function updateConversationAccountsFollows
    (
        $conversationDTO, $status = null
    )
    {
        $follows = $this->getConversationFollowsUsingAccounts($conversationDTO);

        foreach ($follows as $follow) {
            if (is_null($status)) {
                FollowService::updateFollowFillable(
                    follow: $follow,
                    fillable: 'conversation_id',
                    data: $conversationDTO->conversation->id
                );
                continue;
            }
            
            if ($follow->followed_user_id === $conversationDTO->userId) {
                $fillable = 'followable_chat_status';
            }
            
            if ($follow->user_id === $conversationDTO->userId) {
                $fillable = 'followedby_chat_status';
            }
            
            FollowService::updateFollowFillable(
                follow: $follow,
                fillable: $fillable,
                data: $status
            );
        }

        return $follows;
    }

    private function broadcastConversation(ConversationDTO $conversationDTO)
    {
        $event = $this->getEvent($conversationDTO);

        broadcast($event)->toOthers();
    }

    private function getEvent(ConversationDTO $conversationDTO)
    {
        if ($conversationDTO->methodType === 'created' ||
            $conversationDTO->methodType === 'updated') {
            return new NewConversation($conversationDTO);
        }

        return new ConversationResponse($conversationDTO);
    }

    private function validateAccessUsingUserId(ConversationDTO $conversationDTO)
    {
        if ($conversationDTO->conversation->accountableHavingUserId($conversationDTO->userId)) {
            return;
        }

        $this->throwAccountNotFoundException(
            message: "sorry 😞, you do not have access to this conversation",
            data: $conversationDTO
        );
    }

    private function getPaginatedConversations(ConversationDTO $conversationDTO)
    {
        return  Conversation::whereHas('conversationAccounts', 
            function($query) use ($conversationDTO){
                $query->where(function($q) use ($conversationDTO){
                    $q->whereHasMorph('accountable', '*',function($query) use ($conversationDTO){
                        $query->whereUser($conversationDTO->userId);
                    })
                    ->when(count($conversationDTO->states), 
                        function($query) use ($conversationDTO) {
                            $query->whereIn('state',$conversationDTO->states);
                        }
                    );
                });
            }
        )->orderBy('updated_at','desc')->paginate(
            self::PAGINATION
        );
    }

    private function withLoadedConversationRelationship($conversation)
    {
        return $conversation->load('conversationAccounts.accountable.profile.images');
    }

    public function getConversations(ConversationDTO $conversationDTO)
    {
        return $this->getPaginatedConversations(
            $conversationDTO->withStates(['ACCEPT','REQUEST'])
        );
    }

    public function getBlockedConversations(ConversationDTO $conversationDTO)
    {
        return $this->getPaginatedConversations(
            $conversationDTO->withStates(['BLOCK','DECLINE'])
        );
    }

    public function getPendingConversations(ConversationDTO $conversationDTO)
    {
        return $this->getPaginatedConversations(
            $conversationDTO->withStates(['PENDING'])
        );
    }

    public function markAnswer(AnswerDTO $answerDTO)
    {
        $answer = $this->getModel('answer', $answerDTO->answerId);

        $myAccount = $this->getModel($answerDTO->account, $answerDTO->accountId);

        $this->checkMyAccountsAccessToConversation(
            ConversationDTO::new()
                ->withMyChattingAccount($myAccount)
                ->withConversation($answer->answerable->questionable->conversation())
        );

        $mark = (new MarkService())->createMark(
            $answerDTO->markDTO
                ->withMarkable($answer)
                ->withMarkedby($myAccount)
        );
        
        $this->broadcastMark($mark);
            
        return $mark;
    }

    private function broadcastMark($mark)
    {
        broadcast(new NewChatMark($mark));
    }

    public function sendAnswer(QuestionDTO $questionDTO)
    {
        $question = $this->getModel('question', $questionDTO->questionId);

        $myAccount = $this->getModel($questionDTO->account, $questionDTO->accountId);

        $this->checkMyAccountsAccessToConversation(
            ConversationDTO::new()
                ->withMyChattingAccount($myAccount)
                ->withConversation($question->questionable->conversation())
        );

        $questionDTO->answerDTO->chat = true;

        $answer = (new AnswerService())->createAnswer(
            $questionDTO->answerDTO
                ->withAnswerable($question)
                ->withAnsweredby($myAccount)
        );

        $this->broadcastAnswer($answer);
        
        return $answer;
    }

    private function broadcastAnswer($answer)
    {
        broadcast(new NewChatAnswer($answer))->toOthers();
    }

    public function sendMessage(MessageDTO $messageDTO)
    {
        $conversation = $this->getModel($messageDTO->item, $messageDTO->itemId);

        $this->validateAccessUsingUserId(
            ConversationDTO::new()
                ->addData($messageDTO->fromUserId)
                ->withConversation($conversation)
        );

        $messageDTO = $messageDTO->withFromable(
            $this->getMyAccountFromConversationUsingUserId($conversation, $messageDTO->fromUserId)
        );

        $messageDTO = $messageDTO->withToable(
            $this->getOtherAccountFromConversationUsingUserId($conversation, $messageDTO->fromUserId)
        );

        $messageDTO = $messageDTO->withMessageable($conversation);
        
        $message = (new MessageService())->createMessage($messageDTO);
        
        if ($messageDTO->questionDTO) {
            $message = $this->addQuestionToMessage($message, $messageDTO);
        }

        $messageDTO->method = 'created';
        $this->broadcastMessage($messageDTO->withMessage($message));

        return $message;
    }

    private function addQuestionToMessage
    (
        Message $message,
        MessageDTO $messageDTO
    ) : Message
    {
        $messageDTO->questionDTO->questionable = $message;
        $messageDTO->questionDTO->addedby = $message->fromable;
        $messageDTO->questionDTO->state = 'SENT';
        
        (new QuestionService)->createQuestion($messageDTO->questionDTO);

        return $message->refresh();
    }

    private function broadcastMessage(MessageDTO $messageDTO)
    {
        if ($messageDTO->action === 'self') {
            return;
        }

        $event = $this->getMessageEvent($messageDTO);
            
        broadcast($event)->toOthers();
    }

    private function getMessageEvent(MessageDTO $messageDTO)
    {
        if ($messageDTO->method === 'created') {
            return new NewChatMessage($messageDTO);
        }

        if ($messageDTO->method === 'updated') {
            return new UpdateChatMessage($messageDTO);
        }

        return new DeleteChatMessage($messageDTO);
    }

    private function getMyAccountFromConversationUsingUserId($conversation, $userId)
    {
        $account = $conversation->accountableHavingUserId($userId);

        if (is_not_null($account)) {
            return $account;
        }

        $this->throwConversationException(
            message: "sorry 😞, your account does not have access to this conversation",
            data: $dto
        );
    }

    private function getOtherAccountFromConversationUsingUserId($conversation, $userId)
    {
        $account = $conversation->accountableNotHavingUserId($userId);

        if (is_not_null($account)) {
            return $account;
        }

        $this->throwConversationException(
            message: "sorry 😞, the other person's account does not have access to this conversation",
            data: $dto
        );
    }

    public function getMessages(ConversationDTO $conversationDTO)
    {
        $conversationDTO = $this->setConversation($conversationDTO);

        $this->validateAccessUsingUserId($conversationDTO);

        return (new MessageService)->getMessagesBasedOnItem(
            MessageDTO::new()
                ->withMessageable($conversationDTO->conversation)
                ->addData(
                    orderBy: 'updated_at'
                )
        );
    }

    public function deleteMessage(MessageDTO $messageDTO)
    {
        $messageDTO = $this->setMessage($messageDTO);

        $this->deleteMessageQuestions($messageDTO);

        (new MessageService)->deleteMessage($messageDTO);

        $messageDTO->method = 'deleted';
        $this->broadcastMessage($messageDTO);

        if ($messageDTO->action === 'delete') {
            return null;
        }

        return $messageDTO->message->refresh();
    }

    private function setMessage($messageDTO)
    {
        return $messageDTO->withMessage(
            $this->getModel('message', $messageDTO->messageId)
        );
    }

    private function deleteMessageQuestions($messageDTO)
    {
        if ($messageDTO->action === 'self') {
            return;
        }

        foreach ($messageDTO->message->questions as $question) {
            (new QuestionService())->deleteQuestion(
                QuestionDTO::new()->withQuestion($question)
            );
        }
    }

    public function updateMessageState(MessageDTO $messageDTO)
    {
        $messageDTO = $this->setMessage($messageDTO);

        $message = (new MessageService)->updateMessageState($messageDTO);

        $messageDTO->method = 'updated';
        $this->broadcastMessage($messageDTO);

        return $message;
    }
}
