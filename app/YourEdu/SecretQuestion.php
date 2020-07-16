<?php

namespace App\YourEdu;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SecretQuestion extends Model
{
    //
    use SoftDeletes;

    protected $fillable = [
        'question'
    ];

    protected  $with = [
        'possibleAnswers'
    ];

    public function possibleAnswers()
    {
        return $this->morphMany(PossibleAnswer::class,'question');
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
}
