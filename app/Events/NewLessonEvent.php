<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewLessonEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(private array $lessonData)
    {
        //
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        $on = [];
        $on[] = new PrivateChannel("youredu.{$this->lessonData['account']}.{$this->lessonData['accountId']}");
        foreach ($this->lessonData['classes'] as $cl) {
            $on[] = new PresenceChannel("youredu.class.{$cl->id}");
        }
        foreach ($this->lessonData['courses'] as $cl) {
            $on[] = new PresenceChannel("youredu.course.{$cl->id}");
        }
        foreach ($this->lessonData['extracurriculums'] as $cl) {
            $on[] = new PresenceChannel("youredu.extracurriculum.{$cl->id}");
        }
        foreach ($this->lessonData['extracurriculums'] as $cl) {
            $on[] = new PresenceChannel("youredu.extracurriculum.{$cl->id}");
        }
        foreach ($this->lessonData['courseSections'] as $cl) {
            $on[] = new PresenceChannel("youredu.courseSection.{$cl->id}");
        }
        return $on;
    }

    public function broadcastAs()
    {
        return "newLesson";
    }

    public function broadcastWith()
    {
        return [
            'lesson' => $this->lessonData['lesson']
        ];
    }
}
