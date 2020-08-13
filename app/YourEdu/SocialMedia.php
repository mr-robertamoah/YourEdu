<?php

namespace App\YourEdu;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SocialMedia extends Model
{
    //
    use SoftDeletes;
    
    protected $fillable = ['profile_id','username','name', 'url','type','show'];
    
    protected $casts = [
        'show' => 'boolean',
    ];

    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }
}
