<?php

namespace App\Traits;

use App\YourEdu\Flag;

trait HasFlaggableTrait
{
    public function flags()
    {
        return $this->morphMany(Flag::class,'flaggable');
    }

    public function scopeWhereDoesntHaveFlagsFrom($query, $userIds)
    {
        return $query
        ->when(is_array($userIds) && count($userIds), function($query) use($userIds) {
            $query->whereDoesntHave('flags',function($query) use ($userIds){
                $query->whereIn('user_id',$userIds);
            });
        });
    }

    public function scopeWhereDoesntHaveApprovedFlags($query)
    {
        return $query->whereDoesntHave('flags',function($query){
            $query->where('status',"APPROVED");
        });
    }
}
