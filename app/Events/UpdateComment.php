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
    public function __construct($comment,$mainComment)
    {
        Debugbar::info($mainComment);
        $this->commentArray['item'] = class_basename_lower($mainComment->commentable_type);
        $this->commentArray['itemId'] = $mainComment->commentable_id;
        if ($this->commentArray['item'] === 'post') {
            $this->commentArray['account'] = class_basename_lower(get_class($mainComment->commentable->addedby));
            $this->commentArray['accountId'] = $mainComment->commentable->addedby->id;
        } else if ($this->commentArray['item'] === 'book' || 
            $this->commentArray['item'] === 'poem' || 
            $this->commentArray['item'] === 'activity') {
            $this->commentArray['account'] = class_basename_lower(get_class($mainComment->commentable->post->addedby));
            $this->commentArray['accountId'] = $mainComment->commentable->post->addedby->id;
        } else if ($this->commentArray['item'] === 'comment' ||
            $this->commentArray['item'] === 'answer') {
            $this->commentArray['account'] = null;
            $this->commentArray['accountId'] = null;
        } else if ($this->commentArray['item'] === 'discussion') {
            $this->commentArray['account'] = class_basename_lower(get_class($mainComment->commentable->raisedby));
            $this->commentArray['accountId'] = $mainComment->commentable->raisedby->id;
        } else if ($this->commentArray['item'] === 'class') {
            $this->commentArray['account'] = class_basename_lower(get_class($mainComment->commentable->ownedby));
            $this->commentArray['accountId'] = $mainComment->commentable->ownedby->id;
        }
        $this->commentArray['comment'] = $comment;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        $broadcastOn = [
            new Channel('youredu.home'),
            new Channel("youredu.{$this->commentArray['account']}.{$this->commentArray['accountId']}"),
        ];
        if ($this->commentArray['item'] === 'class') {
            $broadcastOn[] = new PrivateChannel("youredu.{$this->commentArray['item']}.{$this->commentArray['itemId']}");
        } else {
            $broadcastOn[] = new Channel("youredu.{$this->commentArray['item']}.{$this->commentArray['itemId']}");
        }
        return $broadcastOn;
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
