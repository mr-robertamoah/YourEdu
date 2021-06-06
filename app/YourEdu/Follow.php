<?php

namespace App\YourEdu;

use Database\Factories\FollowFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Follow extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'followed_user_id','followedby_chat_status',
        'followable_chat_status'];

    public function followable (){
        return $this->morphTo();
    }

    public function followedby (){
        return $this->morphTo();
    }

    public function request()
    {
        return $this->morphOne(Request::class,'requestable');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function involvingBothAccounts($accountOne, $accountTwo)
    {
        return self::query()
            ->whereInvolvesBothAccounts($accountOne, $accountTwo)
            ->get();
    }
    
    public function scopeWithAccounts($query)
    {
        return $query->with(['followable.profile.images','followedby.profile.images']);
    }
    
    public function scopeWhereInvolvesBothAccounts($query, $accountOne, $accountTwo)
    {
        return $query->where(function($query) use ($accountOne, $accountTwo){
            $query->where([
                'followable_type' => $accountOne::class,
                'followable_id' => $accountOne->id,
                'followedby_type' => $accountTwo::class,
                'followedby_id' => $accountTwo->id,
            ]);
        })->orWhere(function($query) use ($accountOne, $accountTwo){
            $query->where([
                'followable_type' => $accountTwo::class,
                'followable_id' => $accountTwo->id,
                'followedby_type' => $accountOne::class,
                'followedby_id' => $accountOne->id,
            ]); 
        });
    }

    public function scopeWhereFollowedbyUser($query, $userId)
    {
        return $query
            ->whereHasMorph('followedby', '*', function($query) use ($userId) {
                $query->whereUser($userId);
            });
    }

    public function scopeWhereFollowedby($query, $account)
    {
        return $query
            ->where(function($query) use ($account) {
                $query->where('followedby_type', $account::class)
                    ->where('followedby_id', $account->id);
            });
    }

    public function scopeWhereFollowable($query, $account)
    {
        return $query
            ->where(function($query) use ($account) {
                $query->where('followable_type', $account::class)
                    ->where('followable_id', $account->id);
            });
    }

    protected static function newFactory()
    {
        return FollowFactory::new();
    }
}
