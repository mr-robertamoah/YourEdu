<?php

namespace App\YourEdu;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Email extends Model
{
    //

    use SoftDeletes;
    
    protected $fillable = ['email','show'];
    
    protected $casts = [
        'show' => 'boolean'
    ];

    public function emailable()
    {
        return $this->morphTo();
    }
}
