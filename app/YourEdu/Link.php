<?php

namespace App\YourEdu;

use Database\Factories\LinkFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Link extends Model
{
    //
    use SoftDeletes, HasFactory;

    protected $fillable = ['name','description','link'];
    
    public function addedby()
    {
        return $this->morphTo();
    }

    public function linkable()
    {
        return $this->morphTo();
    }

    protected static function newFactory()
    {
        return LinkFactory::new();
    }
}
