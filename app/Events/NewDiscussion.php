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

class NewDiscussion implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $discussion;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($discussion)
    {
        $this->discussion = $discussion;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        $account = class_basename_lower($this->discussion->raisedby_type);
        return [
            new Channel('youredu.home'),
            new Channel("youredu.{$account}.{$this->discussion->raisedby_id}")
        ];
    }
    
    public function broadcastAs()
    {
        return 'newDiscussion';
    }
    
    public function broadcastWith()
    {
        return [
            'discussion' => $this->discussion
        ];
    }
}
