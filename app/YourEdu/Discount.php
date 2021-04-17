<?php

namespace App\YourEdu;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Discount extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'name', 'uuid', 'discounted_price', 'percentage', 'expires_at',
    ];

    protected $casts = [
        'exires_at' => 'datetime'
    ];

    public function ownedby()
    {
        return $this->morphTo();
    }

    public function discountables()
    {
        return $this->hasMany(Discountable::class);
    }

    public function requests()
    {
        return $this->morphToMany(Requestable::class, 'requestable', 'requestables');
    }

    protected static function newFactory()
    {
        //
    }
}
