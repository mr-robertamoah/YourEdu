<?php

namespace App\YourEdu;

use Database\Factories\MarkFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mark extends Model
{
    //
    use SoftDeletes,
        HasFactory;

    protected $fillable = ['remark','score','score_over', 'user_id'];

    public function markable()
    {
        return $this->morphTo();
    }

    public function markedby()
    {
        return $this->morphTo();
    }

    public function answer()
    {
        return $this->belongsTo(Answer::class);
    }

    protected static function newFactory()
    {
        return MarkFactory::new();
    }
}
