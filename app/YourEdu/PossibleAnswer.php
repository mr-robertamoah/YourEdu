<?php

namespace App\YourEdu;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PossibleAnswer extends Model
{
    //
    use SoftDeletes;

    protected $fillable = [
        'option', 'state'
    ];

    public function question()
    {
        return $this->morphTo();
    }
}
