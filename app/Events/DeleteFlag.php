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

class DeleteFlag implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $flagInfo;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($flagInfo)
    {
        $this->flagInfo = $flagInfo;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        $account = getAccountString($this->flagInfo->account);
        return [
            new Channel('youredu.home'),
            new Channel("youredu.{$account}.{$this->flagInfo->accountId}")
        ];
    }
    
    public function broadcastAs()
    {
        return 'deleteFlag';
    }
    
    public function broadcastWith()
    {
        return [
            'flagInfo' => $this->flagInfo
        ];
    }
}
