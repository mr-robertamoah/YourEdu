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

class DeletePost implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $postDTO;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($postDTO)
    {
        //
        $this->postDTO = $postDTO;
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
            new Channel("youredu.{$this->postDTO->account}.{$this->postDTO->accountId}")
        ];
    }
    
    public function broadcastAs()
    {
        return 'deletePost';
    }
    
    public function broadcastWith()
    {
        return $this->postDTO;
    }
}
