<?php

namespace App\Events;

use App\DTOs\AccountToJoinItemDTO;
use App\Http\Resources\UserAccountResource;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewAccountToJoinItem implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(private AccountToJoinItemDTO $accountToJoinItemDTO){}

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        $item = class_basename_lower($this->accountToJoinItemDTO->dashboardItem);
        return new Channel("youredu.{$item}.{$this->accountToJoinItemDTO->dashboardItem->id}");
    }
    
    public function broadcastAs()
    {
        $action = capitalize($this->accountToJoinItemDTO->action);
        return "new{$action}";
    }

    public function broadcastWith()
    {
        return [
            "account" => new UserAccountResource($this->accountToJoinItemDTO->account)
        ];
    }
}
