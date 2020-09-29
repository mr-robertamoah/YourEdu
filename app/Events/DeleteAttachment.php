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

class DeleteAttachment implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $attachmentInfo;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($attachmentInfo)
    {
        $this->attachmentInfo = $attachmentInfo;
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
            new Channel("youredu.{$this->attachmentInfo['item']}.{$this->attachmentInfo['itemId']}"),
        ];
    }
    
    public function broadcastAs()
    {
        return 'deleteAttachment';
    }
    
    public function broadcastWith()
    {
        return $this->attachmentInfo;
    }
}
