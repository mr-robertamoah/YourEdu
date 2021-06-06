<?php

namespace App\Events;

use App\Http\Resources\ConversationResource;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ConversationResponse implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(private $conversationDTO){}

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel("youredu.message.{$this->conversationDTO->otherChattingAccount->user_id}");
    }
    
    public function broadcastAs()
    {
        return 'newConversationResponse';
    }
    
    public function broadcastWith()
    {
        return [
            'conversation' => new ConversationResource($this->conversationDTO->conversation),
            'response' => $this->conversationDTO->methodType,
        ];
    }
}
