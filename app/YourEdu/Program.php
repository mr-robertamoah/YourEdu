<?php

namespace App\YourEdu;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Program extends Model
{
    //
    use SoftDeletes;

    protected $fillable = [
        'name','description','rationale'
    ];

    public function facilitators()
    {
        return $this->belongsToMany(Facilitator::class)->withTimestamps();
    }
    
    public function aliases()
    {
        return $this->morphMany(Alias::class,'aliasable');
    }

    public function addedby()
    {
        return $this->morphTo();
    }

    public function attachments()
    {
        return $this->morphMany(PostAttachment::class,'attachedwith');
    }
    
    public function discussions()
    {
        return $this->morphMany(Discussion::class,'discussionon');
    }
}
