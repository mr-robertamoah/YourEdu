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

class NewPost implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $post;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($post)
    {
        $this->post = $post;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        $account = class_basename_lower($this->post->addedby_type);
        $accountId = $this->post->addedby_id;
        return [
            // new Channel('youredu.home.country'),
            new PrivateChannel("youredu.followers{$this->post->addedby->accountType}.{$this->post->addedby->id}"),
            new PrivateChannel("youredu.followings{$this->post->addedby->accountType}.{$this->post->addedby->id}")
        ];
    }

    public function broadcastAs()
    {
        return 'newPost';
    }

    public function broadcastWith()
    {
        return [
            'post' => $this->post
        ];
    }
}
