<?php

namespace App\YourEdu;

use App\User;
use Database\Factories\RequestFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder;

class Request extends Model
{
    use SoftDeletes,
        HasFactory;

    const PENDING = 'PENDING';
    const ACCEPTED = 'ACCEPTED';
    const DECLINED = 'DECLINED';

    protected $fillable = [
        'state', 'data',
    ];

    public function price()
    {
        return $this->morphOne(Price::class, 'priceable');
    }

    public function requestable()
    {
        return $this->morphTo();
    }

    public function messages()
    {
        return $this->morphMany(Message::class, 'messageable');
    }

    public function requestfrom()
    {
        return $this->morphTo();
    }

    public function requestto()
    {
        return $this->morphTo();
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function files()
    {
        return $this->morphToMany(File::class, 'fileable')
            ->withPivot(['state'])->withTimestamps();
    }

    public function audios()
    {
        return $this->morphToMany(Audio::class, 'audioable')
            ->withPivot(['state'])->withTimestamps();
    }

    public function videos()
    {
        return $this->morphToMany(Video::class, 'videoable')
            ->withPivot(['state'])->withTimestamps();
    }

    public function images()
    {
        return $this->morphToMany(Image::class, 'imageable')
            ->withPivot(['state'])->withTimestamps();
    }

    public function allFiles()
    {
        $files = [];

        array_push($files, ...$this->images);
        array_push($files, ...$this->videos);
        array_push($files, ...$this->audios);
        array_push($files, ...$this->files);

        return $files;
    }

    public function requestables()
    {
        return $this->hasMany(Requestable::class);
    }

    public function commissions()
    {
        return $this->morphedByMany(Commission::class, 'requestable', 'requestables');
    }

    public function fees()
    {
        return $this->morphedByMany(Fee::class, 'requestable', 'requestables');
    }

    public function salaries()
    {
        return $this->morphedByMany(Salary::class, 'requestable', 'requestables');
    }

    public function discounts()
    {
        return $this->morphedByMany(Discount::class, 'requestable', 'requestables');
    }

    public function isFollowRequest()
    {
        return $this->requestable_type === 'App\YourEdu\Follow';
    }

    public function isMessageRequest()
    {
        return $this->requestable_type === 'App\YourEdu\Message';
    }

    public function isDiscussionRequest()
    {
        return $this->requestable_type === 'App\YourEdu\Discussion';
    }

    public function isNotDiscussionRequest()
    {
        return !$this->isDiscussionRequest();
    }

    public function isAssessmentRequest()
    {
        return $this->requestable_type === 'App\YourEdu\Assessment';
    }

    public function isNotAssessmentRequest()
    {
        return !$this->isAssessmentRequest();
    }

    public function isMarkerRequest()
    {
        return $this->data && str_contains($this->data, 'marker');
    }

    public function isNotMarkerRequest()
    {
        return !$this->isMarkerRequest();
    }

    public function getOtherAccount($userId)
    {
        if ($this->requestfrom->user_id === $userId) {
            return $this->requestto;
        }

        return $this->requestfrom;
    }

    public function getMyAccount($userId)
    {
        if ($this->requestfrom->user_id === $userId) {
            return $this->requestto;
        }

        return $this->requestfrom;
    }

    public function scopeWhereSentBy(
        $query,
        $account
    ) {
        return $query->where(function ($query) use ($account) {
            $query->where('requestfrom_type', $account::class)
                ->where('requestfrom_id', $account->id);
        });
    }

    public function scopeWhereRequestFromUsers($query, $userIds)
    {
        if (!is_array($userIds)) {
            return $query;
        }

        return $query->where(function ($query) use ($userIds) {
            $query->whereHasMorph('requestfrom', '*', function ($query) use ($userIds) {
                $query->whereUsers($userIds);
            });
        });
    }

    public function scopeWhereRequestFromUser($query, $userIds)
    {
        return $query->where(function ($query) use ($userIds) {
            $query->whereHasMorph('requestfrom', '*', function ($query) use ($userIds) {
                $query->whereUser($userIds);
            });
        });
    }

    public function scopeWhereSentTo(
        $query,
        $account
    ) {
        return $query->where(function ($query) use ($account) {
            $query->where('requestto_type', $account::class)
                ->where('requestto_id', $account->id);
        });
    }

    public function scopeWhereSentByOrSentTo(
        $query,
        $account
    ) {
        return $query->where(function ($query) use ($account) {
            $query->whereSentby($account);
        })->orWhere(function ($query) use ($account) {
            $query->whereSentTo($account);
        });
    }

    public function scopeWhereRequestableTypeNotIn(
        $query,
        $requestableTypes = []
    ) {
        return $query->when(
            count($requestableTypes),
            function ($query) use ($requestableTypes) {
                $query->where(function ($query) use ($requestableTypes) {
                    $query->whereNotIn('requestable_type', $requestableTypes);
                });
            },
            function ($query) {
                return $query;
            }
        );
    }

    public function scopeWhereRequestableTypeIn(
        Builder $query,
        $requestableTypes = []
    ) {
        return $query->when(
            count($requestableTypes),
            function ($query) use ($requestableTypes) {
                $query->where(function ($query) use ($requestableTypes) {
                    $query->whereIn('requestable_type', $requestableTypes);
                });
            },
            function ($query) {
                return $query;
            }
        );
    }

    public function scopeWhereSentToAccountByUser(
        $query,
        $userId,
        $requestto
    ) {
        return $query->where(function ($query) use ($userId, $requestto) {
            $query->where('requestto_type', $requestto::class)
                ->where('requestto_id', $requestto->id)
                ->whereHasMorph('requestfrom', '*', function ($query,) use ($userId) {
                    $query->whereUser($userId);
                });
        });
    }

    public function scopeWhereMarkerRequest($query)
    {
        return $query->where('data', 'like', '%marker%');
    }

    public function scopeWhereSentToAccountByAccount(
        $query,
        $requestfrom,
        $requestto,
    ) {
        return $query->where(function ($query) use ($requestfrom, $requestto) {
            $query->where('requestto_type', $requestto::class)
                ->where('requestto_id', $requestto->id)
                ->where('requestfrom_type', $requestfrom::class)
                ->where('requestfrom_id', $requestfrom->id);
        });
    }

    public function scopeWherePending($query)
    {
        return $query->where(function ($query) {
            $query->where('state', self::PENDING);
        });
    }

    public function scopeWhereFollowRequest($query)
    {
        return $query->where(function ($query) {
            $query->where('requestable_type', 'App\YourEdu\Follow');
        });
    }

    public function scopeWhereDiscussionRequest($query)
    {
        return $query->where(function ($query) {
            $query->where('requestable_type', 'App\YourEdu\Discussion');
        });
    }

    public function scopeWhereMessageRequest($query)
    {
        return $query->where(function ($query) {
            $query->where('requestable_type', 'App\YourEdu\Message');
        });
    }

    public function scopeWhereAssessmentRequest($query)
    {
        return $query->where(function ($query) {
            $query->where('requestable_type', 'App\YourEdu\Assessment');
        });
    }

    protected static function newFactory()
    {
        return RequestFactory::new();
    }
}
