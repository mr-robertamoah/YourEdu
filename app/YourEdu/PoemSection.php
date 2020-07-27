<?php

namespace App\YourEdu;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PoemSection extends Model
{
    //
    use SoftDeletes;

    protected $fillable = [
        'body', 
    ];

    public function poem()
    {
        return $this->belongsTo(Poem::class);
    }
}
