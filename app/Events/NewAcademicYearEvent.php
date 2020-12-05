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

class NewAcademicYearEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $schoolId;
    private $academicYear;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($schoolId,$academicYear)
    {
        $this->schoolId = $schoolId;
        $this->academicYear = $academicYear;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel("youredu.school.$this->schoolId");
    }
    
    public function broadcastAs()
    {
        return 'newAcademicYear';
    }
    
    public function broadcastWith()
    {
        return [
            'academicYear' => $this->academicYear,
        ];
    }
}
