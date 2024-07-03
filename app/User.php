<?php

namespace App;

use App\Traits\HasAnsweredbyTrait;
use App\Traits\AccountCommissionTrait;
use App\Traits\AccountFilesTrait;
use App\Traits\AccountQuestionsTrait;
use App\Traits\AccountSalariesTrait;
use App\Traits\ModelTrait;
use App\YourEdu\Account;
use App\YourEdu\Admin;
use App\YourEdu\Ban;
use App\YourEdu\Employment;
use App\YourEdu\Facilitator;
use App\YourEdu\Follow;
use App\YourEdu\Learner;
use App\YourEdu\Login;
use App\YourEdu\Message;
use App\YourEdu\ParentModel;
use App\YourEdu\PhoneNumber;
use App\YourEdu\Point;
use App\YourEdu\Professional;
use App\YourEdu\Profile;
use App\YourEdu\Question;
use App\YourEdu\Request;
use App\YourEdu\School;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable,
        HasApiTokens,
        SoftDeletes,
        HasFactory,
        AccountSalariesTrait,
        AccountCommissionTrait,
        AccountFilesTrait,
        AccountQuestionsTrait,
        HasAnsweredbyTrait;

    const MINIMUM_ADULT_AGE = 18;
    const MAX_PROFESSIONAL_SLOTS = 3;
    const MAX_SCHOOL_SLOTS = 3;

    protected $fillable = [
        'username', 'email', 'password', 'first_name', 'last_name',
        'other_names', 'username', 'referrer_id', 'secret_question_id',
        'secret_answer', 'dob', 'gender'
    ];

    protected $hidden = [
        'password', 'remember_token', 'secret_question_id',
        'card_brand', 'card_last_four', 'deleted_at', 'referrer_id',
        'stripe_id', 'trial_ends_at', 'profiles'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'dob' => 'datetime',
    ];

    // protected $with = ['learner','admins','parent','professionals','schools'];

    protected $appends = [
        'name', 'age', 'is_superadmin', 'is_supervisoradmin',
    ];

    public $accountType = 'user';

    public function receivesBroadcastNotificationsOn()
    {
        return "youredu.user.{$this->id}";
    }

    public function getNameAttribute()
    {
        if (!$this->other_names || $this->other_names === "") {
            return "{$this->first_name} {$this->last_name}";
        }
        return "{$this->first_name} {$this->other_names} {$this->last_name}";
    }

    public function getAgeAttribute()
    {
        if ($this->dob) {
            return  now()->diffInYears($this->dob);
        }
        return null;
    }

    public function getIsSuperadminAttribute()
    {
        return $this->admins()->where('role', 'SUPERADMIN')->exists();
    }

    public function getIsSupervisoradminAttribute()
    {
        return $this->admins()->where('role', 'SUPERVISOR')->exists();
    }

    public function findForPassport($username)
    {
        return $this->where('username', $username)->first();
    }

    public function account()
    {
        return $this->hasOne(Account::class);
    }

    public function bans()
    {
        return $this->morphMany(Ban::class, 'bannable');
    }

    public function points()
    {
        return $this->hasMany(Point::class);
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

    public function learner()
    {
        return $this->hasOne(Learner::class);
    }

    public function parent()
    {
        return $this->hasOne(ParentModel::class);
    }

    public function schools()
    {
        return $this->hasMany(School::class, 'owner_id');
    }

    public function professionals()
    {
        return $this->hasMany(Professional::class);
    }

    public function phoneNumbers()
    {
        return $this->morphMany(PhoneNumber::class, 'phoneable');
    }

    public function messagesSent()
    {
        return $this->hasMany(Message::class, 'from_user_id');
    }

    public function messagesReceived()
    {
        return $this->hasMany(Message::class, 'to_user_id');
    }

    public function requestsSent()
    {
        return $this->morphMany(Request::class, 'requestfrom');
    }

    public function requestsReceived()
    {
        return $this->morphMany(Request::class, 'requestto');
    }

    public function employed()
    {
        return $this->morphMany(Employment::class, 'employee');
    }

    public function isLearner()
    {
        return $this->learner()->exists();
    }

    public function hasLearnerSlot()
    {
        return !$this->learner()->exists();
    }

    public function hasParentSlot()
    {
        return !$this->parent()->exists();
    }

    public function hasFacilitatorSlot()
    {
        return !$this->facilitator()->exists();
    }

    public function hasProfessionalSlot()
    {
        return $this->professionals()->count() < self::MAX_PROFESSIONAL_SLOTS;
    }

    public function hasSchoolSlot()
    {
        return $this->schools()->count() < self::MAX_SCHOOL_SLOTS;
    }

    public function getAuthorizedIds()
    {
        return [$this->id];
    }

    public function revokeToken()
    {
        return $this->tokens()->latest()
            ->first()?->revoke();
    }

    public function allAccounts()
    {
        $accounts = $this->schools;

        $accounts = $accounts->push(...$this->professionals);

        if ($this->learner) {
            $accounts = $accounts->push($this->learner);
        }

        if ($this->facilitator) {
            $accounts = $accounts->push($this->facilitator);
        }

        return $accounts;
    }

    public function hasSchoolWithAdmin($admin)
    {
        return $this->schools()
            ->whereAdmin($admin)
            ->exists();
    }

    public function isAdult()
    {
        return $this->age >= self::MINIMUM_ADULT_AGE;
    }

    public function scopeWhereSearch($query, $search)
    {
        return $query
            ->where('username', 'like', "%$search%")
            ->orWhere('first_name', 'like', "%$search%")
            ->orWhere('other_names', 'like', "%$search%")
            ->orWhere('last_name', 'like', "%$search%");
    }

    public function scopeWhereFollower($query, $account)
    {
        return $query;
    }

    public function pendingAndServedBans()
    {
        return $this->bans()->where(function ($query) {
            $query->where(function ($query) {
                $query->whereDate('due_date', '>', now())
                    ->whereIn('state', ['PENDING', 'SERVED']);
            })
                ->orWhere(function ($query) {
                    $query->whereNull('due_date')
                        ->whereIn('state', ['PENDING', 'SERVED']);
                });
        })->get();
    }
}
