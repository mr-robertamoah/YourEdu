<?php

namespace App\YourEdu;

use App\User;
use Database\Factories\ProfileFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Profile extends Model
{
    use HasFactory;

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

    public function scopeWhereDoesntHaveFlagsFrom($query, $userIds)
    {
        return $query
            ->when(is_array($userIds) && count($userIds), function($query) use($userIds) {
                $query->whereDoesntHaveMorph('profileable','*',function(Builder $query) use ($userIds){
                    $query->whereHas('flags',function(Builder $query) use ($userIds){
                        $query->whereIn('user_id', $userIds)
                            ->orWhere('status',"APPROVED");
                    });
                });
            });
    }

    public function scopeWherePartOf($query, $item, $itemId, $state)
    {
        if (is_null($state)) {
            $state = 'active';
        }

        $item = capitalize($item);

        $scopeMethod = "whereSpecific{$item}ParticipationById";
        
        return $query
            ->with(['profileable' => function($query) use ($scopeMethod, $itemId) {
                $query->when(
                    $query->getModel()->accountType !== 'school',
                    function($query) use ($scopeMethod, $itemId) {
                        $query->with(['participants' => function($query) use ($scopeMethod, $itemId) {
                                $query->$scopeMethod($itemId);
                            }
                        ]);
                    }
                );
            }])
            ->whereHasMorph('profileable', '*', function($query) use ($scopeMethod, $state, $itemId) {
                $query->when(
                    $query->getModel()->accountType !== 'school',
                    function($query) use ($scopeMethod, $state, $itemId) {
                        $query->whereHas('participants', function($query) use ($scopeMethod, $state, $itemId) {
                            $query
                                ->$scopeMethod($itemId)
                                ->where('state', Str::upper($state));
                        });
                    }
                );
            })
            ->orWhereHasMorph('profileable', '*', function($query) use ($item, $itemId) {
                $query
                    ->when(
                        $item === 'discussion',
                        function($query) use ($itemId) {
                            $query->whereHas('discussions', function($query) use ($itemId) {
                                $query->where('id', $itemId);
                            });
                        }
                    )
                    ->when(
                        $item === 'assessment',
                        function($query) use ($itemId) {
                            $query->whereHas('addedAssessments', function($query) use ($itemId) {
                                $query->where('id', $itemId);
                            });
                        }
                    );
            });
    }

    public function scopeWherePartOfDiscussion($query, $itemId, $state)
    {
        if (is_null($state)) {
            $state = 'active';
        }
        
        return $query
            ->with(['profileable' => function($query) use ($itemId) {
                $query->when(
                    $query->getModel()->accountType !== 'school',
                    function($query) use ($itemId) {
                        $query->with(['participants' => function($query) use ($itemId) {
                                $query->whereSpecificDiscussionParticipationById($itemId);
                            }
                        ]);
                    }
                );
            }])
            ->whereHasMorph('profileable', '*', function($query) use ($state, $itemId) {
                $query->when(
                    $query->getModel()->accountType !== 'school',
                    function($query) use ($state, $itemId) {
                        $query->whereHas('participants', function($query) use ($state, $itemId) {
                            $query
                                ->whereSpecificDiscussionParticipationById($itemId)
                                ->where('state', Str::upper($state));
                        });
                    }
                );
            })
            ->orWhereHasMorph('profileable', '*', function($query) use ($itemId) {
                $query
                    ->whereHas('discussions', function($query) use ($itemId) {
                        $query->where('id', $itemId);
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
        $userId = null,
        $others = false
    )
    {
        if (! str_contains($search, "%")) {
            $search = "%{$search}%";
        }
        
        return $query
            ->when(count($only), 
                function($query) use ($only) {
                    $query->whereIn('profileable_type',$only);
                }
            )
            ->when($account && $accountId,
                function($query) use ($account,$accountId) {
                    $query
                        ->where('profileable_type','!=',"App\\YourEdu\\$account")
                        ->where('profileable_type','!=',$accountId);
                }
            )
            ->when($others && $userId, 
                function($query) use ($userId) {
                    $query->orWhereHasMorph('profileable','*',
                        function($query, $type) use ($userId) {
                            if ($type === 'App\\YourEdu\\School') {
                                $query->where('owner_id', '!=',$userId);
                            } else {
                                $query->where('user_id', '!=',$userId);
                            }
                        }
                    );
                }
            )
            ->whereAccountType($searchAccount)
            ->whereSearchName($search)
            ->with('profileable');
    }

    public function scopeWhereAccountType($query, $accountType)
    {
        return $query->when(strlen($accountType),
            function($query) use ($accountType) {
                $query->where('profileable_type',$accountType);
            }
        );
    }

    public function scopeWhereSearchName($query, $search)
    {
        return $query->where(
            function($query) use ($search) {
                $query->orWhereHasMorph('profileable','*',
                    function($query) use ($search) {
                        $query->searchAccounts($search);
                    }
                )
                ->orWhere('name','like',$search)
                ->orWhere('about','like',$search);
            }
        );
    }

    protected static function newFactory()
    {
        return ProfileFactory::new();
    }
}
