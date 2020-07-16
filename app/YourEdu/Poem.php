<?php

namespace App\YourEdu;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Poem extends Model
{
    //
    use SoftDeletes;

    protected $fillable = [
        'title', 'author', 'about', 'published'
    ];

    protected $casts = [
        'published' => 'datetime'
    ];

    public function addedby()
    {
        return $this->morphTo();
    }

    public function authoredby()
    {
        return $this->morphTo();
    }

    public function poemable()
    {
        return $this->morphTo();
    }

    public function poemSections()
    {
        return $this->hasMany(PoemSection::class);
    }
}
