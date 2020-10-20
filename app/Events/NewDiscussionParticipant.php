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

class NewDiscussionParticipant implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $discussionId;
    private $participant;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($participant,$discussionId)
    {
        $this->participant = $participant;
        $this->discussionId = $discussionId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel("youredu.discussion.{$this->discussionId}");
    }

    public function broadcastAs()
    {
        return 'newDiscussionParticipant';
    }

    public function broadcastWith()
    {
        return [
            'discussionParticipant' => $this->participant,
            'discussionId' => $this->discussionId,
        ];
    }
}
