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
use \Debugbar;

class NewConversation implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $conversation;
    public $user_id;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($conversation, $user_id)
    {
        $this->conversation = $conversation;
        $this->user_id = $user_id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel("youredu.message.{$this->user_id}");
    }
    
    public function broadcastAs()
    {
        return 'newConversation';
    }

    public function broadcastWith()
    {
        return [
            'conversation' => $this->conversation
        ];
    }
}
