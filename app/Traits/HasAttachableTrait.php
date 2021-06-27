<?php

namespace App\Traits;

use App\YourEdu\PostAttachment;

trait HasAttachableTrait
{
    public function attachments()
    {
        return $this->morphMany(PostAttachment::class, 'attachable');
    }
}
