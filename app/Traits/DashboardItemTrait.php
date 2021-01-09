<?php

namespace App\Traits;


/**
 * this is holds functions belonging to school, course, extracurriculum, programs, classes
 */
trait DashboardItemTrait
{
    /**
     * get user ids of accounts authorized to access this course
     */
    public function getAuthorizedUserIds()
    {
        $item = getAccountString($this);
        $userIds = [];

        if ($item === 'school') {
            array_push($userIds,...getAdminIds($this));
        }
        if ($this->ownedby) {
            $userIds[] = $this->ownedby->user_id ? $this->ownedby->user_id : $this->ownedby->owner_id;
        }
        if ($this->learners) {
            array_push($userIds,$this->learners->pluck('user_id')->toArray());
            array_push($userIds,$this->learners()->with('parents')->get()->pluck('parents.user_id')->toArray());
        }
        if ($this->facilitators) {
            array_push($userIds,$this->facilitators->pluck('user_id')->toArray());
        }
        if ($this->professionals) {
            array_push($userIds,$this->professionals->pluck('user_id')->toArray());
        }
        if ($this->schools) {
            foreach ($this->schools as $school) {                
                array_push($userIds,$school->getAuthorizedUserIds());
            }
        }
        // error_log("$userIds"); 
        return $userIds;
    }
}
