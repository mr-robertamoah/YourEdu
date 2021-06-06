<?php

namespace App\YourEdu;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salariable extends Model
{
    use HasFactory;

    public function salaries()
    {
        return $this->belongsTo(Salary::class);
    }

    public function employments()
    {
        return $this->belongsTo(Employment::class);
    }

    public function salariable()
    {
        return $this->morphTo();
    }

    public function scopeWhereSpecificSalariable($query, $account)
    {
        return $query->where(function ($query) use ($account) {
            $query
                ->where('salariable_type', $account::class)
                ->where('salariable_id', $account->id);
        });
    }

    protected static function newFactory()
    {
        //
    }
}
