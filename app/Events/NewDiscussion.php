<?php

namespace App\Events;

use App\Http\Resources\DiscussionResource;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewDiscussion implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(private $discussionDTO){}

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return [
            new Channel("youredu.{$this->discussionDTO->discussion->raisedby->accountType}.{$this->discussionDTO->discussion->raisedby_id}")
        ];
    }
    
    public function broadcastAs()
    {
        return 'newDiscussion';
    }
    
    public function broadcastWith()
    {
        return [
            'discussion' => new DiscussionResource($this->discussionDTO->discussion)
        ];
    }
}
