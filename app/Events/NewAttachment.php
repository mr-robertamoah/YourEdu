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

class NewAttachment implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $attachmentArray;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($attachmentArray)
    {
        $this->attachmentArray = $attachmentArray;
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
            new Channel("youredu.{$this->attachmentArray['item']}.{$this->attachmentArray['itemId']}")
        ];
    }
    
    public function broadcastAs()
    {
        return 'newAttachment';
    }
    
    public function broadcastWith()
    {
        return $this->attachmentArray;
    }
}
