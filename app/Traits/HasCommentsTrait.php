<?php

namespace App\Traits;

use App\YourEdu\Comment;

trait HasCommentsTrait
{
    public function comments()
    {
        return $this->morphMany(Comment::class,'commentable');
    }

    public function latestComments()
    {
        return $this->comments()
            ->orderby('updated_at','desc')
            ->take(2)
            ->get();
    }

    public function commentsCount()
    {
        return $this->comments()->count();
    }
}
