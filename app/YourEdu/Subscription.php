<?php

namespace App\YourEdu;

use Database\Factories\SubscriptionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subscription extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'name', 'amount', 'description', 'for', 'period'
    ];

    public function ownedby()
    {
        return $this->morphTo();
    }

    public function subscribable()
    {
        return $this->morphTo();
    }

    protected static function newFactory()
    {
        return SubscriptionFactory::new();
    }
}
