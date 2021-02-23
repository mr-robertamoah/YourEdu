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

class DeleteExtracurriculumEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(private array $extracurriculumData)
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
        $on[] = new PrivateChannel("youredu.{$this->extracurriculumData['account']}.{$this->extracurriculumData['accountId']}");
        if (is_array($this->extracurriculumData['classes'])) {            
            foreach ($this->extracurriculumData['classes'] as $cl) {
                $on[] = new PresenceChannel("youredu.class.{$cl->id}");
            }
        }
        if (is_array($this->extracurriculumData['programs'])) {            
            foreach ($this->extracurriculumData['programs'] as $cl) {
                $on[] = new PresenceChannel("youredu.program.{$cl->id}");
            }
        }
        return $on;
    }

    public function broadcastAs()
    {
        return "deleteExtracurriculum";
    }

    public function broadcastWith()
    {
        return [
            'extracurriculumId' => $this->extracurriculumData['extracurriculumId']
        ];
    }
}
