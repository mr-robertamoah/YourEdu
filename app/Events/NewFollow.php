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

class NewFollow implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $action;
    public $follower;
    public $userId;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($follower, $userId, $action)
    {
        $this->action =  $action;
        $this->follower =  $follower;
        $this->userId = $userId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel("youredu.user.{$this->userId}");
    }
    
    public function broadcastAs()
    {
        return 'newFollower';
    }
    
    public function broadcastWith()
    {
        return [
            'action' => $this->action,
            'follower' => $this->follower,
        ];
    }
}
