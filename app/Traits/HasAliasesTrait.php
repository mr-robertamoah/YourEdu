<?php

namespace App\Traits;

use App\YourEdu\Alias;

trait HasAliasesTrait
{

    public function aliases()
    {
        return $this->morphMany(Alias::class, 'aliasable');
    }

    public function hasAliasWithName($name)
    {
        return $this->aliases()->whereName($name)->exists();
    }

    public function scopeWhereName($query, $name)
    {
        return $query->where('name', $name);
    }
}
