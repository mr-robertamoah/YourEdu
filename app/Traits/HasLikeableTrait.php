<?php

namespace App\Traits;

use App\YourEdu\Like;

trait HasLikeableTrait
{
    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }
}
