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

class DeleteLike implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $likeInfo;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($likeInfo)
    {
        $this->likeInfo = $likeInfo;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return [
            new Channel('youredu.home'),
            new Channel("youredu.{$this->likeInfo['item']}.{$this->likeInfo['itemId']}"),
            new Channel("youredu.{$this->likeInfo['itemBelongsTo']}.{$this->likeInfo['itemBelongsToId']}")
        ];
    }
    
    public function broadcastAs()
    {
        return 'deleteLike';
    }
    
    public function broadcastWith()
    {
        return $this->likeInfo;
    }
}
