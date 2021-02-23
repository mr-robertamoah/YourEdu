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

class DeletePost implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $postInfo;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($postInfo)
    {
        //
        $this->postInfo = $postInfo;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        $account = class_basename_lower($this->postInfo['account']);
        return [
            new Channel('youredu.home'),
            new Channel("youredu.{$account}.{$this->postInfo['accountId']}")
        ];
    }
    
    public function broadcastAs()
    {
        return 'deletePost';
    }
    
    public function broadcastWith()
    {
        return $this->postInfo;
    }
}
