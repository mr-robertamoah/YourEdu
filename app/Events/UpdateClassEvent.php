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

class UpdateClassEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $classArray;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($classArray)
    {
        $this->classArray = $classArray;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        $channels = [];
        if ($this->classArray['account'] === 'school') {
            $channels[] = new PrivateChannel("youredu.school.{$this->classArray['accountId']}");
        } else {
            $channels[] = new PrivateChannel("youredu.class.{$this->classArray['class']->id}");
        }
        return $channels;
    }

    public function broadcastAs()
    {
        return 'updateClass';
    }
    
    public function broadcastWith()
    {
        return [
            'class' => $this->classArray['class'],
            'classResource' => $this->classArray['classResource'],
        ];
    }
}
