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
use Debugbar;

class UpdateComment implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $commentArray;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($commentArray)
    {
        Debugbar::info($commentArray);
        $this->commentArray['item'] = getAccountString($commentArray['mainComment']->commentable_type);
        $this->commentArray['itemId'] = $commentArray['mainComment']->commentable_id;
        if ($this->commentArray['item'] === 'post') {
            $this->commentArray['account'] = getAccountString(get_class($commentArray['mainComment']->commentable->postedby));
            $this->commentArray['accountId'] = $commentArray['mainComment']->commentable->postedby->id;
        } else if ($this->commentArray['item'] === 'book' || 
            $this->commentArray['item'] === 'poem' || 
            $this->commentArray['item'] === 'activity') {
            $this->commentArray['account'] = getAccountString(get_class($commentArray['mainComment']->commentable->post->postedby));
            $this->commentArray['accountId'] = $commentArray['mainComment']->commentable->post->postedby->id;
        } else if ($this->commentArray['item'] === 'comment' ||
            $this->commentArray['item'] === 'answer') {
            $this->commentArray['account'] = null;
            $this->commentArray['accountId'] = null;
        }
        $this->commentArray['comment'] = $commentArray['comment'];
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
            new Channel("youredu.{$this->commentArray['account']}.{$this->commentArray['accountId']}"),
            new Channel("youredu.{$this->commentArray['item']}.{$this->commentArray['itemId']}"),
        ];
    }
    
    public function broadcastAs()
    {
        return 'updateComment';
    }
    
    public function broadcastWith()
    {
        return $this->commentArray;
    }
}
