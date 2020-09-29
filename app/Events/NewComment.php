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

class NewComment implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $newArray;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($commentArray)
    {
        $this->newArray['comment'] = $commentArray['comment'];
        $this->newArray['item'] = $commentArray['item'];
        $this->newArray['itemId'] = $commentArray['itemId'];
        $this->newArray['account'] =  getAccountString(get_class($commentArray['commentable_owner']));
        $this->newArray['accountId'] = $commentArray['commentable_owner']->id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return [
            new Channel('youredu.home'),
            new Channel("youredu.{$this->newArray['account']}.{$this->newArray['accountId']}"),
            new Channel("youredu.{$this->newArray['item']}.{$this->newArray['itemId']}"),
        ];
    }
    
    public function broadcastAs()
    {
        return 'newComment';
    }
    
    public function broadcastWith()
    {
        return $this->newArray;
    }
}
