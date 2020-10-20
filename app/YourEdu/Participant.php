<?php

namespace App\YourEdu;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Participant extends Model
{
    use SoftDeletes;

    protected $fillable =  ['user_id','state'];

    protected $touches =  ['participation'];

    public function accountable()
    {
        return $this->morphTo();
    }

    public function participation()
    {
        return $this->morphTo();
    }
}
