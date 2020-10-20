<?php

namespace App\Jobs;

use App\Http\Resources\ParticipantResource;
use App\Notifications\RemoveDiscussionParticipantNotification;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification;
use \Debugbar;

class RemoveDiscussionParticipantNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $userId;
    private $title;
    private $participant;
    private $type;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($userId,$title,$participant,$type)
    {
        $this->userId = $userId;
        $this->title = $title;
        $this->participant = $participant;
        $this->type = $type;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->type === 'one') {            
            User::find($this->userId)->notify(new RemoveDiscussionParticipantNotification(
                new ParticipantResource($this->participant),
                "you have been removed from the discussion with title {$this->title}"
            ));
        } else {
            $users = User::whereIn('id',$this->userId)->get();
            Debugbar::info($users);
            Notification::send($users,new RemoveDiscussionParticipantNotification(
                new ParticipantResource($this->participant),
                "this participant just left the discussion with title {$this->title}"
            ));
        }
    }
}
