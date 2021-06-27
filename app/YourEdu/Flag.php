<?php

namespace App\YourEdu;

use App\User;
use Database\Factories\FlagFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Flag extends Model
{
    use SoftDeletes,
        HasFactory;

    protected $fillable = [
        'user_id',  'flag_id', 'admin_id', 'status', 'reason'
    ];

    // protected $touches = [
    //     'flaggable'
    // ];

    public function flaggedby()
    {
        return $this->morphTo();
    }

    public function flaggable()
    {
        return $this->morphTo();
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function flags()
    {
        return $this->hasMany(Admin::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function bans()
    {
        return $this->morphOne(Ban::class, 'issuedfor');
    }

    protected static function newFactory()
    {
        return FlagFactory::new();
    }
}
