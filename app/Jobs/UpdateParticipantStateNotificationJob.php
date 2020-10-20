<?php

namespace App\Jobs;

use App\Http\Resources\ParticipantResource;
use App\Notifications\UpdateParticipantStateNotification;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateParticipantStateNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $userId;
    private $action;
    private $account;
    private $title;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($userId,$action,$title,$account)
    {
        $this->userId = $userId;
        $this->action = $action;
        $this->title = $title;
        $this->account = $account;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        User::find($this->userId)->notify(new UpdateParticipantStateNotification(
            new ParticipantResource($this->account),
            "your participating account for the discussion with title {$this->title} is now {$this->action}."));
    }
}
