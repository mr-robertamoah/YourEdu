<?php

namespace App\Traits;

use App\YourEdu\AssessmentSection;
use App\YourEdu\Commission;
use App\YourEdu\Lesson;
use App\YourEdu\Request;
use Illuminate\Database\Eloquent\Model;

/**
 * this trait will be for the holding methods and properties belonging
 * to learners, parents, facilitators, professionals and schools
 */
trait AccountTrait
{    

    public $accountType = '';

    public function __construct(array $attributes = []) {
        $this->setAccountType();

        parent::__construct($attributes);
    }

    private function setAccountType()
    {
        $this->accountType = class_basename_lower($this);
    }
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

    /**
     * this will help determine whether or not an account has a 
     * free resource with lessons
     * resources like courses, classes, extracurriculum, program
     */
    public function hasFreeResources()
    {
        $userId = $this->user_id ?? $this->owner_id;
        return (bool) Lesson::whereHasMorph('lessonable',[
            'App\\YourEdu\\Course',
            'App\\YourEdu\\Extracurriculum',
            'App\\YourEdu\\ClassModel',
        ],function($query,$type) use($userId) {
            $query->whereHasMorph('ownedby','*',function($query,$type) use($userId) {
                if ($type === 'App\\YourEdu\\School') {
                    $query->where('owner_id',$userId);
                } else {
                    $query->where('user_id',$userId);
                }
            })->where(function($query) use($type){
                $q = $query->doesntHave('subscriptions')
                    ->doesntHave('prices');
                if ($type === 'App\\YourEdu\\ClassModel') {
                    $q = $q->doesntHave('fees');
                }
                $q;
            });
        })->count();
    }

    public function scopeSearchAccounts($query,$search)
    {
        return $query
            ->where(function($query) use ($search) {
                if ($this->accountType === 'school') {
                    $query
                        ->where('company_name','like',"%$search%")
                        ->orWhere('about','like',"%$search%");
                } else {
                    $query->where('name','like',"%$search%");
                }
            });
    }

    public function usesFacilitationDetails()
    {
        return $this->accountType === 'professional' || 
            $this->accountType === 'facilitator';
    }
    
    public function getAdminIds() : array
    {
        if ($this->accountType !== 'school') {
            return [];
        }
        
        $fromUserIds = $this->admins->pluck('user_id')->toArray();
        
        if (in_array($this->owner->id,$fromUserIds)) {        
            return $fromUserIds;
        }
    
        $fromUserIds[] = $this->owner->id;
        return $fromUserIds;
    }

    public function getParentIds() : array
    {
        return $this->parents->pluck('user_id')->toArray();
    }
    
    public function authorizedIds() : array
    {
        if ($this->accountType === 'school') {
            return $this->getAdminIds();
        }
        
        if ($this->accountType === 'learner') {
            $ids = $this->getParentIds();
            array_push($ids, $this->user_id);
            return $ids;
        }
    
        return [$this->user_id];
    }

    public function notifyUser($notification)
    {
        if ($this->owner) {
            $this->owner->notify($notification);
        } else {
            $this->user->notify($notification);
        }
    }

    public function allCollaborations()
    {
        return $this->collaborations->merge($this->addedCollaborations)
            ->sortByDesc('created_at')->unique();
    }

    public function getCommissionShare(Model $commissionFor)
    {
        return $this->getCommission($commissionFor)?->percent;
    }

    public function getCommission(Model $commissionFor): Commission | null
    {
        return $this->commissions()
            ->whereHasMorph('for',$commissionFor::class)
            ->first();
    }

    public function addedAssessments()
    {
        return $this->morphMany(AssessmentSection::class,'addedby');
    }

    /**
     * check if account doesnt have a request
     *
     * this function checks if $this account doesnt have a request for a specific
     *
     * @param Model $requestable this is a model which is the morphTo for a request
     * @return bool
     **/
    public function doesntHavePendingRequestFor(Model $requestable)
    {
        return $this->requestsReceived()
            ->where('requestable_type',$requestable::class)
            ->where('requestable_id',$requestable->id)
            ->where('state',Request::PENDING)
            ->count() === 0;
    }
}
