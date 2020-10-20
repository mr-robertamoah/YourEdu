<?php

namespace App\Jobs;

use App\Events\DeleteAttachment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AttachmentRemovedJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $attachmentId;
    private $item;
    private $itemId;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($attachmentId,$item,$itemId)
    {
        $this->attachmentId = $attachmentId;
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
        broadcast(new DeleteAttachment([
            'attachmentId' => $this->attachmentId,
            'item' => $this->item,
            'itemId' => $this->itemId,
        ]))->toOthers();
    }
}
