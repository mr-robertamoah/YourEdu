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

class DeleteDiscussionMessage implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $discussionId;
    public $messageId;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($messageId, $discussionId)
    {
        $this->messageId = $messageId;
        $this->discussionId = $discussionId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel("youredu.discussion.{$this->discussionId}");
    }
    
    public function broadcastAs()
    {
        return 'deleteDiscussionMessage';
    }
    
    public function broadcastWith()
    {
        return [
            'messageId' => $this->messageId,
        ];
    }
}
