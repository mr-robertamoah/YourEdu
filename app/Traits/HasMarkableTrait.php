<?php

namespace App\Traits;

use App\YourEdu\Mark;

trait HasMarkableTrait
{
    public function marks()
    {
        return $this->morphMany(Mark::class,'markable');
    }

    public function isMarkedbyUser($userId)
    {
        return $this->marks()
            ->whereHasMorph('markedby', '*', function($query) use ($userId) {
                $query->whereUser($userId);
            })
            ->exists();
    }

    public function isNotMarkedbyUser($userId)
    {
        return ! $this->isMarkedbyUser($userId);
    }

    public function isNotAutoMarkable()
    {
        if ($this->answerable->doesntHavePossibleAnswers()) {
            return true;
        }

        if ($this->answerable->doesntHaveCorrectPossibleAnswers()) {
            return true;
        }

        return false;
    }

    public function scopeWhereNotMarked($query)
    {
        return $query->where(
                function($query) {
                    $query->doesntHave('marks');
                }
            );
    }

    public function scopeWhereNotMarkedby($query, $account)
    {
        return $query->where(function($query) use($account) {
                $query->whereDoesntHave('marks', function($query) use($account) {
                    $query
                        ->where('markedby_type', $account::class)
                        ->where('markedby_id', $account->id);
                });
            });
    }
}
