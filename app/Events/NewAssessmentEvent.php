<?php

namespace App\Events;

use App\Http\Resources\AssessmentResource;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewAssessmentEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(private $assessment) {}

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        $channels = [];

        $this->assessment->items()->each(function($item) use ($channels) {
            $itemName = class_basename_lower($item);

            $channels[] = new PrivateChannel("youredu.{$itemName}.{$item->id}");
        });

        return $channels;
    }

    public function broadcastAs()
    {
        return "newAssessment";
    }

    public function broadcastWith()
    {
        return [
            'assessment' => new AssessmentResource($this->assessment)
        ];
    }
}
