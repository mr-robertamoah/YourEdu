<?php

namespace App\YourEdu;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lessonable extends Model
{
    use HasFactory;

    protected $fillable = ["lesson_number", "type"];

    protected $table = "lessonables";

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    public function lessonable()
    {
        return $this->morphTo();
    }

    public function itemable()
    {
        return $this->morphTo();
    }

    protected static function newFactory()
    {
        
    }
}
