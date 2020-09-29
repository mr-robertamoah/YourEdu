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

class DeleteSave implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $saveInfo;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($saveInfo)
    {
        $this->saveInfo = $saveInfo;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        $account = getAccountString($this->saveInfo->account);
        return [
            new Channel('youredu.home'),
            new Channel("youredu.{$account}.{$this->saveInfo->accountId}")
        ];
    }
    
    public function broadcastAs()
    {
        return 'deleteSave';
    }
    
    public function broadcastWith()
    {
        return [
            'saveInfo' => $this->saveInfo
        ];
    }
}
