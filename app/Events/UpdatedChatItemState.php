<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UpdatedChatItemState implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $item;
    public $chatItem;
    public $userId;
    public $conversationId;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($item,$chatItem,$userId,$conversationId)
    {
        $this->item = $item;
        $this->chatItem = $chatItem;
        $this->userId = $userId;
        $this->conversationId = $conversationId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel("youredu.message.{$this->userId}");
    }
    
    public function broadcastAs()
    {
        return 'updatedChatItemState';
    }
    
    public function broadcastWith()
    {
        return [
            'item' => $this->item,
            'chatItem' => $this->chatItem,
            'conversationId' => $this->conversationId,
        ];
    }
}
