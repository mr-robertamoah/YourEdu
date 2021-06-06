<?php

namespace App\YourEdu;

use App\User;
use Database\Factories\PointFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Point extends Model
{
    use HasFactory;

    protected $touches = ['pointable'];

    protected $fillable = ['value','user_id'];

    public function pointable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected static function newFactory()
    {
        return PointFactory::new();
    }
}
