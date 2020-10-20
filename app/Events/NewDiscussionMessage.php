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

class NewDiscussionMessage implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $discussionId;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($message, $discussionId)
    {
        $this->message = $message;
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
        return 'newDiscussionMessage';
    }
    
    public function broadcastWith()
    {
        return [
            'message' => $this->message
        ];
    }
}
