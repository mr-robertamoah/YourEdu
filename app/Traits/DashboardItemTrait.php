<?php

namespace App\Traits;

use Illuminate\Support\Collection;

/**
 * this is holds functions belonging to school, course, extracurriculum, programs, classes
 */
trait DashboardItemTrait
{
    /**
     * get user ids of accounts authorized to access this course
     */
    public function getAuthorizedUserIds(bool $authority = false) : array
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
            if ($this->learners) {
                array_push($userIds,...$this->learners->pluck('user_id')->toArray());
                array_push($userIds,...$this->learners()->with('parents')->get()->pluck('parents.user_id')->toArray());
            }
            if ($this->facilitators) {
                array_push($userIds,...$this->facilitators->pluck('user_id')->toArray());
            }
            if ($this->professionals) {
                array_push($userIds,...$this->professionals->pluck('user_id')->toArray());
            }
        }
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
        
        return array_unique($userIds);
    }

    public function hasAccess(int $userId)
    {
        return in_array($userId, $this->getAuthorizedUserIds());
    }

    public function scopeSearchItems($query,$search)
    {
        return $query->where(function($q) use ($search){
            $q->where('name','like',"%$search%")
                ->orWhere('description','like',"%$search%");
        });
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
}
