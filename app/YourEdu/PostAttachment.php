<?php

namespace App\YourEdu;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PostAttachment extends Model
{
    use SoftDeletes;

    const ATTACHEDBY_ACCOUNT_TYPES = [
        'professional', 'facilitator', 'school', 'admin'
    ];

    protected $fillable = ['note'];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function attachedby()
    {
        return $this->morphTo();
    }

    public function attachable()
    {
        return $this->morphTo();
    }

    public function attachedwith()
    {
        return $this->morphTo();
    }
}
