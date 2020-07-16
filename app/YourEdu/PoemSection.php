<?php

namespace App\YourEdu;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PoemSection extends Model
{
    //
    use SoftDeletes;

    public function poem()
    {
        return $this->belongsTo(Poem::class);
    }
}
