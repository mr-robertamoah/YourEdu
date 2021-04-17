<?php

namespace App\YourEdu;

use App\User;
use Database\Factories\SaveFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Save extends Model
{
    use SoftDeletes,
        HasFactory;

    protected $fillable = ['user_id'];

    public function saveable()
    {
        return $this->morphTo();
    }

    public function savedby()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected static function newFactory()
    {
        return SaveFactory::new();
    }
}
