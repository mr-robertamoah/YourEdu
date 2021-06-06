<?php

namespace App\Events;

use App\Http\Resources\UserAccountResource;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewAssessmentMarker implements ShouldBroadcast
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
        return new Channel("youredu.assessment.{$this->assessmentDTO->assesment->id}");
    }

    public function broadcastAs()
    {
        return "newMarker";
    }

    public function broadcastWith()
    {
        return [
            'marker' => new UserAccountResource($this->assessmentDTO->assessmentable),
        ];
    }
}
