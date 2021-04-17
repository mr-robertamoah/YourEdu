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

    protected static function newFactory()
    {
        //
    }
}
