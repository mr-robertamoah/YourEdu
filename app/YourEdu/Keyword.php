<?php

namespace App\YourEdu;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Keyword extends Model
{
    //
    use SoftDeletes;

    public function likes(){
        return $this->morphMany(Like::class,'likeable');
    }

    public function lesson(){
        return $this->belongsTo(Lesson::class);
    }

    public function keywordable(){
        return $this->morphTo();
    }

    public function comments(){
        return $this->morphMany(Comment::class,'commentable');
    }
}
