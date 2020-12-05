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

class NewLike implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $likeArray;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($likeArray)
    {
        $this->likeArray = $likeArray;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return [
            // new Channel('youredu.home'),
            new Channel("youredu.{$this->likeArray['item']}.{$this->likeArray['itemId']}"),
            new Channel("youredu.{$this->likeArray['itemBelongsTo']}.{$this->likeArray['itemBelongsToId']}")
        ];
    }
    
    public function broadcastAs()
    {
        return 'newLike';
    }
    
    public function broadcastWith()
    {
        return $this->likeArray;
    }
}
