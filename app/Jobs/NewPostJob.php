<?php

namespace App\Jobs;

use App\DTOs\PostDTO;
use App\Events\NewPost;
use App\Http\Resources\PostResource;
use App\YourEdu\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NewPostJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct
    (
        private Post $post, 
        private PostDTO $postDTO
    )
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
        broadcast(new NewPost(
            new PostResource($this->post),
            $this->postDTO->socketId
        ))->toOthers();
    }
}
