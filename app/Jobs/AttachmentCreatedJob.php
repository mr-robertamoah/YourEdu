<?php

namespace App\Jobs;

use App\Events\NewAttachment;
use App\Http\Resources\PostAttachmentResource;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AttachmentCreatedJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $attachment;
    private $item;
    private $itemId;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($attachment,$item,$itemId)
    {
        $this->attachment = $attachment;
        $this->item = $item;
        $this->itemId = $itemId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        broadcast(new NewAttachment([
            'attachment' =>  new PostAttachmentResource($this->attachment),
            'item' => $this->item,
            'itemId' => $this->itemId
        ]))->toOthers();
    }
}
