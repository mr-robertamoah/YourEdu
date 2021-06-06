<?php

namespace App\Traits;

trait HasSocialMediaTrait
{
    public function scopeWhereFiltered($query, $postsDTO)
    {
        $methodName = 'addedby';
        if (class_basename_lower($this) === 'discussion') {
            $methodName = 'raisedby';
        }

        return $query
        ->when(is_not_null($postsDTO->user) && $postsDTO->mine, function($query) use($methodName) {
            $query->whereHasMorph($methodName,'*',function($query){
                $query->where('user_id', (int)request()->user);
            });
        })->when(is_not_null($postsDTO->user) && $postsDTO->followings, function($query) use($postsDTO, $methodName) {
            $query->whereHasMorph($methodName,'*',function($query) use($postsDTO) {
                $query->whereHas('follows',function($query) use($postsDTO) {
                    $query->whereHasMorph('followable', function($query) use($postsDTO) {
                        $query->whereUser($postsDTO->user->id);
                    });
                });
            });
        })->when(is_not_null($postsDTO->user) && $postsDTO->followers, function($query) use($postsDTO, $methodName) {
            $query->whereHasMorph($methodName,'*',function($query) use($postsDTO) {
                $query->whereHas('followings',function($query) use($postsDTO) {
                    $query->whereHasMorph('followedby', function($query) use($postsDTO) {
                        $query->whereUser($postsDTO->user->id);
                    });
                });
            });
        })->when($postsDTO->attachments, function($query) use ($postsDTO) {
            $query->whereHas('attachments',function($query) use ($postsDTO) {
                $query->where('attachedwith_type', $postsDTO->attachedWith)
                    ->where('attachedwith_id', $postsDTO->attachedWithId);
            });
        });
    }
}
