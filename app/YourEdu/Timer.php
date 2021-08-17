<?php

namespace App\YourEdu;

use App\Traits\HasAddedbyTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Timer extends Model
{
    use HasFactory,
        HasAddedbyTrait;

    protected $fillable = [
        'start_time', 'end_time'
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function timeable()
    {
        return $this->morphTo();
    }

    public function hasMoreTime()
    {
        return now()->diffInMilliseconds($this->end_time, false) > 0;
    }

    protected static function newFactory()
    {
        //
    }
}
