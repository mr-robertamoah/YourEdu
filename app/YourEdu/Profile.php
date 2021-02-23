<?php

namespace App\YourEdu;

use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Profile extends Model
{
    //
    protected $fillable = [
        'user_id', 'name', 'about', 'interests', 'occupation', 'website', 
        'company', 'location', 'address',
    ];

    protected $appends = [
        'url'
    ];

    public function getUrlAttribute()
    {
        return $this->images()->where('state','PUBLIC')->where('thumbnail',1)->exists() ? 
        asset("assets/{$this->images()->where('state','PUBLIC')->where('thumbnail',1)->latest()->first()->path}") :
        asset('storage/default.webp');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function profileable()
    {
        return $this->morphTo();
    }

    public function socials()
    {
        return $this->hasMany(SocialMedia::class);
    }

    public function files()
    {
        return $this->morphToMany(File::class,'fileable')
        ->withPivot(['state'])->withTimestamps();
    }

    public function audios()
    {
        return $this->morphToMany(Audio::class,'audioable')
        ->withPivot(['state'])->withTimestamps();
    }

    public function videos()
    {
        return $this->morphToMany(Video::class,'videoable')
        ->withPivot(['state'])->withTimestamps();
    }

    public function images()
    {
        return $this->morphToMany(Image::class,'imageable')
        ->withPivot(['state','thumbnail'])->withTimestamps();
    }

    public function scopeHasNoFlags($query, $parentsLearnerUserIds)
    {
        return $query->whereDoesntHaveMorph('profileable','*',function(Builder $query) use ($parentsLearnerUserIds){
            $query->whereHas('flags',function(Builder $query) use ($parentsLearnerUserIds){
                $query->whereIn('user_id', $parentsLearnerUserIds)
                    ->orWhere('status',"APPROVED");
            });
        });
    }

    public function scopeSearch
    (
        Builder $query, 
        $search = '',
        $searchAccount = '',
        $account = '',
        $accountId = '',
        $only = [],
    )
    {
        return $query
            ->whereIn('profileable_type',$only)
            ->when(strlen($searchAccount),
                function($query) use ($searchAccount) {
                    $query
                        ->where('profileable_type',$searchAccount);
                }
            )
            ->when($account && $accountId,
                function($query) use ($account,$accountId) {
                    $query
                        ->where('profileable_type','!=',"App\\YourEdu\\$account")
                        ->where('profileable_type','!=',$accountId);
                }
            )
            ->where(
                function($query) use ($search) {
                    $query->whereHasMorph('profileable','*',
                        function($query) use ($search) {
                            $query->searchAccounts($search);
                        }
                    )
                    ->orWhere('name','like',$search)
                    ->orWhere('about','like',$search);
                }
            )->with('profileable');
    }
}
