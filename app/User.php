<?php

namespace App;

use App\YourEdu\Account;
use App\YourEdu\Admin;
use App\YourEdu\Facilitator;
use App\YourEdu\Follow;
use App\YourEdu\Learner;
use App\YourEdu\Login;
use App\YourEdu\ParentModel;
use App\YourEdu\PhoneNumber;
use App\YourEdu\Professional;
use App\YourEdu\Profile;
use App\YourEdu\Request;
use App\YourEdu\School;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'email', 'password', 'first_name', 'last_name', 
        'other_names', 'username', 'referrer_id', 'secret_question_id',
        'secret_answer', 'dob', 'gender'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'secret_question_id', 
        'card_brand', 'card_last_four', 'deleted_at', 'referrer_id',
        'stripe_id', 'trial_ends_at', 'profiles'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'dob' => 'datetime',
    ];

    // protected $with = ['learner','admins','parent','professionals','schools'];

    protected $appends = ['full_name','age','is_superadmin',
        'is_supervisoradmin','is_groupadmin','is_classadmin',
        'is_schooladmin','is_learner','is_parent','is_facilitator',
        'has_professionals','has_schools', 'owned_profiles','follow_requests'
    ];

    public function getFollowRequestsAttribute()
    {
        $requests = Request::where('requestable_type','App\YourEdu\Follow')
            ->where('state','PENDING')
            ->whereHasMorph('requestto','*',function(Builder $builder){
                $builder->where('user_id',$this->id);
            })->count();

        return $requests;
    }
    
    public function getFullNameAttribute()
    {
        if (!$this->other_names || $this->other_names === "") {
            return "{$this->first_name} {$this->last_name}" ;
        }
        return "{$this->first_name} {$this->other_names} {$this->last_name}" ;
    }

    public function getOwnedProfilesAttribute()
    {
        $data = [];
        $profiles = null;

        $profiles = $this->profiles;

        if (is_null($profiles)) {
            return null;
        }

        $i = 0;
        foreach ($profiles as $profile) {
            // $data[$i] = $profile->profileable_type;
            if ($profile->profileable_type === 'App\\YourEdu\\ParentModel') {
                $data[$i] = [
                    'account_id' => $profile->profileable_id,
                    'account_type' => 'parent',
                    'profile_name' => $profile->name,
                    'profile_url' => $profile->url,
                    'profile' => $profile->profileable_type,
                ];
            }
            if ($profile->profileable_type === 'App\\YourEdu\\Facilitator') {
                $data[$i] = [
                    'account_id' => $profile->profileable_id,
                    'account_type' => 'facilitator',
                    'profile_name' => $profile->name,
                    'profile_url' => $profile->url,
                    'profile' => $profile->profileable_type,
                ];
            }
            
            if ($profile->profileable_type === 'App\\YourEdu\\Learner') {
                $data[$i] = [
                    'account_id' => $profile->profileable_id,
                    'account_type' => 'learner',
                    'profile_name' => $profile->name,
                    'profile_url' => $profile->url,
                    'profile' => $profile->profileable_type,
                ];
            }
            
            if ($profile->profileable_type === 'App\\YourEdu\\Professional') {
                $data[$i] = [
                    'account_id' => $profile->profileable_id,
                    'account_type' => 'professional',
                    'profile_name' => $profile->name,
                    'profile_url' => $profile->url,
                    'profile' => $profile->profileable_type,
                ];
            }
            
            if ($profile->profileable_type === 'App\\YourEdu\\School') {
                $data[$i] = [
                    'account_id' => $profile->profileable_id,
                    'account_type' => 'school',
                    'profile_name' => $profile->name,
                    'profile_url' => $profile->url,
                    'profile' => $profile->profileable_type,
                ];
            }


            $i++;
        }
        return $data;
    }

    public function getAgeAttribute()
    {
        if ($this->dob) {
            return  now()->diffInYears($this->dob) ;
        }
        return null ;
    }

    public function getIsSuperadminAttribute()
    {
        return count($this->whereHas('admins',function(Builder $query){
            $query->where('role','SUPERADMIN');
        })->get()) > 0 ? true : false;
    }

    public function getIsSupervisoradminAttribute()
    {
        return count($this->whereHas('admins',function(Builder $query){
            $query->where('role','SUPERVISOR');
        })->get()) > 0 ? true : false;
    }

    public function getIsSchooladminAttribute()
    {
        return count($this->whereHas('admins',function(Builder $query){
            $query->where('role','SCHOOLADMIN');
        })->get()) > 0 ? true : false;
    }

    public function getIsClassadminAttribute()
    {
        return count($this->whereHas('admins',function(Builder $query){
            $query->where('role','CLASSADMIN');
        })->get()) > 0 ? true : false;
    }

    public function getIsGroupadminAttribute()
    {
        return count($this->whereHas('admins',function(Builder $query){
            $query->where('role','GROUPADMIN');
        })->get()) > 0 ? true : false;
    }

    public function getIsLearnerAttribute()
    {
        return $this->learner()->exists();
    }

    public function getIsParentAttribute()
    {
        return $this->parent()->exists();
    }

    public function getIsFacilitatorAttribute()
    {
        return $this->facilitator()->exists();
    }

    public function getHasProfessionalsAttribute()
    {
        return $this->professionals()->exists();
    }

    public function getHasSchoolsAttribute()
    {
        return count($this->whereHas('schools.owner',function(Builder $query){
            $query->where('id',$this->id);
        })->get()) > 0 ? true : false;
        return $this->schools()->exists();
    }

    public function findForPassport($username)
    {
        return $this->where('username', $username)->first();
    }

    public function account()
    {
       return $this->hasOne(Account::class);
    }

    public function profiles()
    {
       return $this->hasMany(Profile::class);
    }

    public function logins()
    {
       return $this->hasMany(Login::class);
    }

    public function facilitator()
    {
       return $this->hasOne(Facilitator::class);
    }

    public function admins()
    {
       return $this->hasMany(Admin::class);
    }

    public function learner(){
        return $this->hasOne(Learner::class);
    }

    public function parent(){
        return $this->hasOne(ParentModel::class);
    }

    public function schools(){
        return $this->hasMany(School::class,'owner_id');
    }

    public function professionals(){
        return $this->hasMany(Professional::class);
    }

    public function phoneNumbers(){
        return $this->morphMany(PhoneNumber::class,'phoneable');
    }

    public function follows()
    {
        return $this->hasMany(Follow::class);
    }
    
}
