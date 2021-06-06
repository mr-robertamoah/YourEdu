<?php

namespace App\Events;

use App\Http\Resources\DiscussionPendingParticipantsResource;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewDiscussionPendingParticipant implements ShouldBroadcastNow
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
        return new Channel("youredu.discussion.{$this->discussionDTO->participant->participation_id}");
    }

    public function broadcastAs()
    {
        return 'newPendingParticipant';
    }

    public function broadcastWith()
    {
        return [
            'pendingParticipant' => new DiscussionPendingParticipantsResource($this->discussionDTO->participant->accountable),
        ];
    }
}
