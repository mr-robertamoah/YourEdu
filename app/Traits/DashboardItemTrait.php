<?php

namespace App\Traits;

use App\YourEdu\Collaboration;
use Illuminate\Support\Collection;

/**
 * this is holds functions belonging to school, course, extracurriculum, programs, classes
 */
trait DashboardItemTrait
{
    /**
     * get user ids of accounts authorized to access this course
     */
    public function getAuthorizedUserIds
    (
        bool $authority = false,
        bool $onlyMain = false,
        bool $deep = false,
    ) : array
    {
        //deal with resources (which means 
        // item being attached to an item in order for its resources to be used) later
        $item = class_basename_lower($this);
        $userIds = [];

        if ($item === 'school') {
            array_push($userIds,...$this->getAdminIds());
        }

        if ($this->ownedby) {
            $userIds[] = $this->ownedby->user_id ? $this->ownedby->user_id : $this->ownedby->owner_id;
        }

        if (!$authority) {            
            if ($this->learners && !$onlyMain) {
                array_push($userIds,...$this->learners->pluck('user_id')->toArray());
                array_push($userIds,...$this->learners()->with('parents')->get()->pluck('parents.user_id')->toArray());
            }

            if ($this->facilitators) {
                array_push($userIds,...$this->facilitators->pluck('user_id')->toArray());
            }

            if ($this->professionals) {
                array_push($userIds,...$this->professionals->pluck('user_id')->toArray());
            }

            if ($this->usesFacilitationDetail()) {
                array_push($userIds,
                    ...$this->facilitationDetailsAccountables()
                        ->pluck('user_id')->toArray()
                );
            }
        }
        if ($deep) {
            
            if ($this->schools) {
                foreach ($this->schools as $school) {                
                    array_push($userIds,...$school->getAuthorizedUserIds());
                }
            }
            if ($this->courses) {
                $this->courses->each(function ($course) use ($userIds) {
                    array_push($userIds,...$course->getAuthorizedUserIds());
                });
            }
            if ($this->courseSections) {
                $this->courseSections->each(function ($section) use ($userIds) {
                    array_push($userIds,...$section->course->getAuthorizedUserIds());
                });
            }
            if ($this->extracurriculums) {
                $this->extracurriculums->each(function ($extracurriculum) use ($userIds) {
                    array_push($userIds,...$extracurriculum->getAuthorizedUserIds());
                });
            }
            if ($this->classes) {
                $this->classes->each(function ($class) use ($userIds) {
                    array_push($userIds,...$class->getAuthorizedUserIds());
                });
            }
            if ($this->programs) {
                $this->programs->each(function ($program) use ($userIds) {
                    array_push($userIds,...$program->getAuthorizedUserIds());
                });
            }
        }
        
        return array_unique($userIds);
    }

    public function usesFacilitationDetail()
    {
        $item = class_basename_lower($this);

        return $item === 'course' || $item === 'program'
            || $item === 'class';
    }

    public function doesntUseFacilitationDetail()
    {
        return !$this->usesFacilitationDetail();
    }

    public function hasAccess(int $userId)
    {
        return in_array($userId, $this->getAuthorizedUserIds());
    }
    
    public function getPaymentTypes()
    {
        $data = new Collection();
        if ($this->fees) {
            $data = $data->merge($this->fees);
        }
        if ($this->subscriptions) {
            $data = $data->merge($this->subscriptions);
        }
        if ($this->prices) {
            $data = $data->merge($this->prices);
        }

        return $data;
    }

    public function hasDiscussion()
    {
        return $this->discussions->count() > 0;
    }

    public function doesntHaveDiscussion()
    {
        return !$this->hasDiscussion();
    }

    public function discussion()
    {
        return $this->discussions->first();
    }

    public function scopeSearchItems($query,$search)
    {
        return $query->where(function($q) use ($search){
            $q->where('name','like',"%$search%")
                ->orWhere('description','like',"%$search%");
        });
    }

    public function scopeWhereOwnedOrFacilitating($query, $account)
    {
        return $query->where(function($query) use ($account) {
            $query->whereOwnedby($account);
        })->orWhere(function($query) use ($account) {
            $query->whereFacilitating($account);
        });   
    }

    public function scopeWhereFacilitating($query, $account)
    {
        return $query->where(function($query) use ($account) {
            if ($account->accountType !== 'school') {
                
                $query->whereHas("{$account->accountType}s",function($query) use ($account) {
                    $query->where('user_id', $account->user_id);
                });
            }
        })->orWhere(function($query) use ($account) {
            $query->whereHas('facilitationDetails',function($query) use ($account) {
                $query->whereHasMorph('accountable', '*', 
                    function($query, $type) use ($account) {
                        if ($type === Collaboration::class) {
                            $query->whereCollaborationable($account);
                        }
                        if ($type !== Collaboration::class) {                            
                            $query->where('user_id', $account->user_id);
                        }
                    });
            });
        });   
    }

    public function scopeWhereOwnedby($query, $account)
    {
        return $query
            ->where('ownedby_type', $account::class)
            ->where('ownedby_id', $account->id);
    }
    
    public function allFiles()
    {
        $files = $this->images;
        $files = $files->merge($this->videos);
        $files = $files->merge($this->audios);
        $files = $files->merge($this->files);

        return $files;
    }
}
