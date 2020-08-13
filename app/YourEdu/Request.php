<?php

namespace App\YourEdu;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Request extends Model
{
    //
    use SoftDeletes;

    protected $fillable = ['state'];

    public function price()
    {
        return $this->morphOne(Price::class,'priceable');
    }

    public function requestable()
    {
        return $this->morphTo();
    }

    public function requestfrom()
    {
        return $this->morphTo();
    }

    public function requestto()
    {
        return $this->morphTo();
    }

    public function comments()
    {
        return $this->morphMany(Comment::class,'commentable');
    }
}
