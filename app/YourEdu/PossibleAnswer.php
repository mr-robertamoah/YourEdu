<?php

namespace App\YourEdu;

use Database\Factories\PossibleAnswerFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PossibleAnswer extends Model
{
    //
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'option', 'state', 'position'
    ];

    public function question()
    {
        return $this->morphTo();
    }

    protected static function newFactory()
    {
        return PossibleAnswerFactory::new();
    }
}
