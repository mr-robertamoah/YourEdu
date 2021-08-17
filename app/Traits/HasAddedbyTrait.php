<?php

namespace App\Traits;

trait HasAddedbyTrait
{
    public function addedby()
    {
        return $this->morphTo();
    }

    public function isAddedby($addedby)
    {
        return $this->whereAddedby($addedby)->exists();
    }

    public function isAddedbyUser($userId)
    {
        return $this
            ->whereAddedbyUser($userId)
            ->exists();
    }

    public function scopeWhenAddedby($query, $addedby)
    {
        return $query
            ->when($addedby, function ($query) use ($addedby) {
                $query->whereAddedby($addedby);
            });
    }

    public function scopeWhereAddedbyUser($query, $userId)
    {
        return $query
            ->whereHasMorph('addedby', '*', function ($query) use ($userId) {
                $query->whereUser($userId);
            });
    }

    public function scopeWhereAddedby($query, $addedby)
    {
        return $query
            ->where('addedby_type', $addedby::class)
            ->where('addedby_id', $addedby->id);
    }
}
