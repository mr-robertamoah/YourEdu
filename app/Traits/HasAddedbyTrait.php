<?php

namespace App\Traits;

trait HasAddedbyTrait
{
    public function addedby()
    {
        return $this->morphTo();
    }

    public function scopeWhenAddedby($query, $addedby)
    {
        return $query
            ->when($addedby, function ($query) use ($addedby) {
                $query->whereAddedby($addedby);
            });
    }

    public function scopeWhereAddedby($query, $addedby)
    {
        return $query
            ->where('addedby_type', $addedby::class)
            ->where('addedby_id', $addedby->id);
    }
}
