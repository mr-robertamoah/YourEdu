<?php

namespace App\Traits;

/**
 * this is for courses, programs, classes, extracurriculum
 */
trait NotOwnedByTrait
{
    public function scopeNotOwnedby($query,$accountClass,$accountId)
    {
        return $query->whereNotNull('ownedby_type')
            ->whereHasMorph('ownedby','*',
                function($query,$type) use ($accountClass, $accountId) {
                    if ($type === $accountClass) {
                        $query->where('id','!=',$accountId);
                    } else {
                        $query;
                    }
                }
            );
    }
}
