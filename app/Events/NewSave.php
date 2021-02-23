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

class NewSave implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $saveArray;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($saveArray)
    {
        $this->saveArray = $saveArray;
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
        if ($this->saveArray->type === 'comment') {
            $account = class_basename_lower($this->saveArray->save->saveable->commentedby_type);
            $accountId = $this->saveArray->save->saveable->commentedby_id;
        } else if ($this->saveArray->type === 'post') {
            $account = class_basename_lower($this->saveArray->save->saveable->postedby_type);
            $accountId = $this->saveArray->save->saveable->postedby_id;
        } else if ($this->saveArray->type === 'answer') {
            $account = class_basename_lower($this->saveArray->save->saveable->answeredby_type);
            $accountId = $this->saveArray->save->saveable->answeredby_id;
        }
        return [
            new Channel('youredu.home'),
            new Channel("youredu.{$account}.{$accountId}")
        ];
    }
    
    public function broadcastAs()
    {
        return 'newSave';
    }
    
    public function broadcastWith()
    {
        return [
            'save' => $this->saveArray->save
        ];
    }
}
