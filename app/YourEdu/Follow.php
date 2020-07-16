<?php

namespace App\YourEdu;

use Illuminate\Database\Eloquent\Model;

class Follow extends Model
{
    //

    public function followable (){
        return $this->morphTo();
    }

    public function followedby (){
        return $this->morphTo();
    }
}
