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

class DeleteCourseEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $courseData;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($courseData)
    {
        //
        $this->courseData = $courseData;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        $on = [];
        $on[] = new PrivateChannel("youredu.{$this->courseData['account']}.{$this->courseData['accountId']}");
        foreach ($this->courseData['classes'] as $cl) {
            $on[] = new PresenceChannel("youredu.class.{$cl->id}");
        }
        return $on;
    }

    public function broadcastAs()
    {
        return "deleteCourse";
    }

    public function broadcastWith()
    {
        return [
            'courseId' => $this->courseData['courseId']
        ];
    }
}
