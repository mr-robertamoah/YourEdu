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

class NewFlag implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $flagArray;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($flagArray)
    {
        $this->flagArray = $flagArray;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        $account = null;
        $accountId = null;
        if ($this->flagArray->type === 'comment') {
            $account = class_basename_lower($this->flagArray->flag->flaggable->commentedby_type);
            $accountId = $this->flagArray->flag->flaggable->commentedby_id;
        } else if ($this->flagArray->type === 'post') {
            $account = class_basename_lower($this->flagArray->flag->flaggable->addedby_type);
            $accountId = $this->flagArray->flag->flaggable->addedby_id;
        }
        return [
            new Channel('youredu.home'),
            new Channel("youredu.{$account}.{$accountId}")
        ];
    }
    
    public function broadcastAs()
    {
        return 'newFlag';
    }
    
    public function broadcastWith()
    {
        return [
            'flagArray' => $this->flagArray
        ];
    }
}
