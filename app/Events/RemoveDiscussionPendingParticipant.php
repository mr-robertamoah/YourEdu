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

class RemoveDiscussionPendingParticipant implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $pendingParticipant;
    public $discussionId;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($pendingParticipant, $discussionId)
    {
        $this->pendingParticipant = $pendingParticipant;
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
        return 'removeDiscussionPendingParticipant';
    }

    public function broadcastWith()
    {
        return [
            'pendingParticipant' => $this->pendingParticipant,
            'discussionId' => $this->discussionId,
        ];
    }
}
