<?php

namespace App\Events;

use App\Http\Resources\DiscussionMessageResource;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use \Debugbar;

class NewDiscussionMessage implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(private $messageDTO){}

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel("youredu.discussion.{$this->messageDTO->message->messageable->id}");
    }
    
    public function broadcastAs()
    {
        return 'newDiscussionMessage';
    }
    
    public function broadcastWith()
    {
        return [
            'message' => new DiscussionMessageResource($this->messageDTO->message)
        ];
    }
}
