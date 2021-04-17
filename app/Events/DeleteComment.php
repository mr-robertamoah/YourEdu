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

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(private $commentDTO){}

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {        
        $broadcastOn = [
            new Channel('youredu.home'),
        ];

        if ($this->commentDTO->item) {
            $channel = 'Illuminate\Broadcasting\Channel';
            if ($this->commentDTO->item === 'class') {
                $channel = 'Illuminate\Broadcasting\PrivateChannel';
            }

            $broadcastOn[] = new $channel("youredu.{$this->commentDTO->item}.{$this->commentDTO->itemId}");
        } 
        
        if ($this->commentDTO->account) {
            $broadcastOn[] = new Channel("youredu.{$this->commentDTO->account}.{$this->commentDTO->accountId}");
        }
        
        return $broadcastOn;
    }
    
    public function broadcastAs()
    {
        return 'deleteComment';
    }
    
    public function broadcastWith()
    {
        return [
            'commentId' => $this->commentDTO->commentId
        ];
    }
}
