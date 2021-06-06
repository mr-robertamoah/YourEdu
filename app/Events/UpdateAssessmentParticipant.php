<?php

namespace App\Events;

use App\Http\Resources\ParticipantResource;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UpdateAssessmentParticipant implements ShouldBroadcast
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
        return 'updatedParticipant';
    }

    public function broadcastWith()
    {
        return [
            'participant' => new ParticipantResource($this->assessmentDTO->participant->refresh()),
        ];
    }
}
