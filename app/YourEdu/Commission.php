<?php

namespace App\YourEdu;

use Illuminate\Database\Eloquent\Model;

class Commission extends Model
{
    //

    protected $fillable = ['percent'];

    protected $casts = ['percent' => 'double'];

    public function addedby()
    {
        return $this->morphTo();
    }

    public function ownedby()
    {
        return $this->morphTo();
    }

    public function commissionables()
    {
        return $this->hasMany(Commissionable::class);
    }

    public function requests()
    {
        return $this->morphToMany(Request::class, 'requestable', 'requestables');
    }

    public function scopeWhereCommissionable($query, $commissionable)
    {
        return $query->where(function($query) use ($commissionable) {
            $query->whereHas('commissionables', function($query) use ($commissionable) {
                $query
                    ->where('commissionable_type', $commissionable::class)
                    ->where('commissionable_id', $commissionable->id);
            });
        });
    }
}
