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
    public function hasBan()
    {
        return $this->bans()->where(function($query){
            $query->where(function($query){
                $query->whereDate('due_date','>',now())
                    ->whereIn('state',['PENDING','SERVED']);
            })
            ->orWhere(function($query){
                $query->whereNull('due_date')
                    ->whereIn('state',['PENDING','SERVED']);
            });
        });
    }
}
