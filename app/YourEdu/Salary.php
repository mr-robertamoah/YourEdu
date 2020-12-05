<?php

namespace App\YourEdu;

use Illuminate\Database\Eloquent\Model;

class Salary extends Model
{
    //

    protected $fillable = ['amount','period','currency','employment_id'];

    public function employment()
    {
        return $this->belongsTo(Employment::class);
    }
}
