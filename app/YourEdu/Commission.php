<?php

namespace App\YourEdu;

use Illuminate\Database\Eloquent\Model;

class Commission extends Model
{
    //

    protected $fillable = ['percent'];

    protected $casts = ['percent' => 'double'];

    public function for()
    {
        return $this->morphTo();
    }

    public function ownedby()
    {
        return $this->morphTo();
    }
}
