<?php

namespace App\YourEdu;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PostAttachment extends Model
{
    //
    use SoftDeletes;

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
}
