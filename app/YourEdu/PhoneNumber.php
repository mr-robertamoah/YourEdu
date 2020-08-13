<?php

namespace App\YourEdu;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PhoneNumber extends Model
{
    //
    use SoftDeletes;
    
    protected $fillable = ['country_code','show','phone_number', 'mobile_money'];
    
    protected $casts = [
        'show' => 'boolean',
        'mobile_money' => 'boolean',
    ];
    public function phoneable()
    {
        return $this->morphTo();
    }
}
