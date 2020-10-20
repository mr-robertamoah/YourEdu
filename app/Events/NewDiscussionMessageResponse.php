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

class NewDiscussionMessageResponse implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $userId;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($message,$userId)
    {
        $this->message = $message;
        $this->userId = $userId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return [
            new PrivateChannel("youredu.user.{$this->userId}"),
            new Channel("youredu.discussion.{$this->message->messageable_id}")
        ];
    }
    
    public function broadcastAs()
    {
        return 'newDiscussionMessageResponse';
    }
    
    public function broadcastWith()
    {
        return [
            'message' => $this->message,
        ];
    }
}
