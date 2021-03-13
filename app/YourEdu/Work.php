<?php

namespace App\YourEdu;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Work extends Model
{
    //
    use SoftDeletes;

    protected $table = 'work';

    public function assessment()
    {
        return $this->belongsTo(Assessment::class);
    }
    
    public function workable()
    {
        return $this->morphTo();
    }

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    public function reportDetail()
    {
        return $this->belongsTo(ReportDetail::class);
    }
}
