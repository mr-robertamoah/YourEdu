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

class NewRequestMessage implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $message;
    private $requestId;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($message,$requestId)
    {
        $this->message = $message;
        $this->requestId = $requestId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel("youredu.request.{$this->requestId}");
    }
    
    public function broadcastAs()
    {
        return 'newRequestMessage';
    }
    
    public function broadcastWith()
    {
        return [
            'message' => $this->message
        ];
    }
}
