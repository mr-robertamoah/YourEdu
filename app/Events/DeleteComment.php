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

class DeleteComment implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $commentInfo;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($commentInfo)
    {
        $this->commentInfo =  $commentInfo;
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
            new Channel("youredu.{$this->commentInfo['account']}.{$this->commentInfo['accountId']}"),
            new Channel("youredu.{$this->commentInfo['item']}.{$this->commentInfo['itemId']}")
        ];
    }
    
    public function broadcastAs()
    {
        return 'deleteComment';
    }
    
    public function broadcastWith()
    {
        return $this->commentInfo;
    }
}
