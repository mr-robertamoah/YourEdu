<?php

namespace App\YourEdu;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expession extends Model
{
    //
    use SoftDeletes;

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function expressionable()
    {
        return $this->morphTo();
    }

    public function comments()
    {
        return $this->morphMany(Comment::class,'commentable');
    }
}
