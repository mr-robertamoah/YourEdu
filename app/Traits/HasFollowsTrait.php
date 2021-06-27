<?php

namespace App\Traits;

use App\YourEdu\Follow;

trait HasFollowsTrait
{
    public function follows()
    {
        return $this->morphMany(Follow::class, 'followable');
    }

    public function followings()
    {
        return $this->morphMany(Follow::class, 'followedby');
    }

    public function isFollowedbyUser($userId)
    {
        return $this->follows()
            ->whereHasMorph('followedby', '*', function ($query) use ($userId) {
                $query->whereUser($userId);
            })
            ->exists();
    }

    public function isFollowableUser($userId)
    {
        return $this->followsings()
            ->whereHasMorph('followable', '*', function ($query) use ($userId) {
                $query->whereUser($userId);
            })
            ->exists();
    }
}
