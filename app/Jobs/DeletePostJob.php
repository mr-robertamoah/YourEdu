<?php

namespace App\Jobs;

use App\DTOs\PostDTO;
use App\Events\DeletePost;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DeletePostJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(private PostDTO $postDTO)
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        broadcast(new DeletePost([
            'postId' => $this->postDTO->postId,
            'account' => $this->postDTO->account,
            'accountId' => $this->postDTO->accountId,
        ]))->toOthers();
    }
}
