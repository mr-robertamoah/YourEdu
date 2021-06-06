<?php

namespace App\YourEdu;

use Database\Factories\AdmissionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Admission extends Model
{
    use SoftDeletes,
        HasFactory;

    protected $fillable = [
        'learner_id', 'grade_id', 'school_id', 'state', 'type'
    ];

    public function price()
    {
        return $this->morphOne(Price::class,'priceable');
    }

    public function grade()
    {
        return $this->belongsTo(Price::class);
    }

    public function learner()
    {
        return $this->belongsTo(Learner::class);
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function admmissionfrom()
    {
        return $this->morphTo();
    }

    public function admmissionto()
    {
        return $this->morphTo();
    }

    public function comments()
    {
        return $this->morphMany(Comment::class,'commentable');
    }

    public function assessments()
    {
        return $this->morphByMany(Assessment::class,'assessmentable');
    }

    protected static function newFactory()
    {
        return AdmissionFactory::new();
    }
    
}
