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

class NewSchoolable implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $type;
    private $account;
    private $schoolId;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($type,$account,$schoolId)
    {
        $this->type = $type;
        $this->account = $account;
        $this->schoolId = $schoolId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PresenceChannel("youredu.school.{$this->schoolId}");
    }
    
    public function broadcastAs()
    {
        return 'newAttachableAccount';
    }
    
    public function broadcastWith()
    {
        return [
            'type' => $this->type,
            'account' => $this->account,
        ];
    }
}
