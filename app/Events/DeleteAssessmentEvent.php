<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DeleteAssessmentEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(private $assessmentDTO) {}

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        $channels = [];

        $this->assessmentDTO->assessmentables->each(function($item) use ($channels) {
            $itemName = class_basename_lower($item);

            $channels[] = new PrivateChannel("youredu.{$itemName}.{$item->id}");
        });

        return $channels;
    }

    public function broadcastAs()
    {
        return "deleteAssessment";
    }

    public function broadcastWith()
    {
        return [
            'assessmentId' => $this->assessmentDTO->assessmentId
        ];
    }
}
