<?php

namespace App\Events;

use App\Http\Resources\PostAttachmentResource;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewAttachment implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(private $attachmentDTO)
    {
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return [
            new Channel("youredu.{$this->attachmentDTO->item}.{$this->attachmentDTO->itemId}")
        ];
    }

    public function broadcastAs()
    {
        return 'newAttachment';
    }

    public function broadcastWith()
    {
        return [
            'attachment' => new PostAttachmentResource($this->attachmentDTO->attachment)
        ];
    }
}
