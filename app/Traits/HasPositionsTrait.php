<?php

namespace App\Traits;

trait HasPositionsTrait
{
    public function scopeOrderedByPosition($query)
    {
        return $query->orderBy('position', 'asc');
    }
}
