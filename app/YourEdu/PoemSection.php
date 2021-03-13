<?php

namespace App\YourEdu;

use Database\Factories\PoemSectionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PoemSection extends Model
{
    //
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'body', 
    ];

    public function poem()
    {
        return $this->belongsTo(Poem::class);
    }

    protected static function newFactory()
    {
        return PoemSectionFactory::new();
    }
}
