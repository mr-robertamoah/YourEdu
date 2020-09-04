<?php

namespace App\YourEdu;

use Illuminate\Database\Eloquent\Model;

class Alias extends Model
{
    //

    protected $fillable = [
        'name','description'
    ];

    public function addedby()
    {
        return $this->morphTo();
    }

    public function aliasable()
    {
        return $this->morphTo();
    }
}
