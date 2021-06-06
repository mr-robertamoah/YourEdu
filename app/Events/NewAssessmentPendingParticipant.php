<?php

namespace App\Events;

use App\Http\Resources\DiscussionPendingParticipantsResource;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewAssessmentPendingParticipant implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(private $assessmentDTO){}

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel("youredu.assessment.{$this->assessmentDTO->participant->participation_id}");
    }

    public function broadcastAs()
    {
        return 'newPendingParticipant';
    }

    public function broadcastWith()
    {
        return [
            'pendingParticipant' => new DiscussionPendingParticipantsResource($this->assessmentDTO->participant->accountable),
        ];
    }
}
