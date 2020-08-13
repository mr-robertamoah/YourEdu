<?php

namespace App\YourEdu;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mark extends Model
{
    //
    use SoftDeletes;

    protected $fillable = ['remark','score','score_over'];

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
}
