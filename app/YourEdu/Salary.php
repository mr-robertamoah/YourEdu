<?php

namespace App\YourEdu;

use Illuminate\Database\Eloquent\Model;

class Salary extends Model
{
    protected $fillable = ['amount','period','currency','name'];

    public function addedby()
    {
        return $this->morphTo();
    }

    public function ownedby()
    {
        return $this->morphTo();
    }

    public function salariables()
    {
        return $this->hasMany(Salariable::class);
    }

    public function requests()
    {
        return $this->morphToMany(Request::class, 'requestable', 'requestables');
    }

    public function scopewhereHasSpecificSalariable($query, $item)
    {
        return $query->salariables()
            ->where(function($query) use ($item) {
                $query->where('salariable_type', $item::class)
                    ->where('salariable_id', $item->id);
            });
    }
}
