<?php

namespace App\YourEdu;

use Illuminate\Database\Eloquent\Model;

class Verification extends Model
{
    //

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function verifiable()
    {
        return $this->morphTo();
    }
    
}
