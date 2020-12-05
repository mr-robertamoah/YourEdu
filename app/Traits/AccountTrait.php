<?php

namespace App\Traits;

/**
 * this trait will be for the holding methods and properties belonging
 * to learners, parents, facilitators, professionals and schools
 */
trait AccountTrait
{    
    /**
     * this helps determine whether the account is currently having a ban
     */
    public function scopeHasBan($query)
    {
        return $query->whereHas('bans',function($query){
            $query->whereDate('due_date','>',now())
                ->orWhereIn('state',['PENDING','SERVED']);
        });
    }
}
