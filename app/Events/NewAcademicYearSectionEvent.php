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

class NewAcademicYearSectionEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $schoolId;
    private $academicYearSection;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($schoolId,$academicYearSection)
    {
        $this->schoolId = $schoolId;
        $this->academicYearSection = $academicYearSection;
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
        return 'newAcademicYearSection';
    }
    
    public function broadcastWith()
    {
        return [
            'academicYearSection' => $this->academicYearSection,
        ];
    }
}
