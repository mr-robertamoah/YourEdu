<?php

namespace App\YourEdu;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourseSection extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'description'];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function lessons()
    {
        return $this->morphToMany(Lesson::class,'lessonable','lessonables')
            ->withPivot(['lesson_number', 'type'])->withTimestamps();
    }

    public function lessonables()
    {
        return $this->morphMany(Lessonable::class, 'lessonable');
    }

    public function lastLesson()
    {
        return $this->lessonables()->whereNotNull('lesson_number')->last();
    }

    public function assessments()
    {
        return $this->morphByMany(Assessment::class,'assessmentable');
    }

    public function scopeSearchItems($query,$search)
    {
        return $query->where(function($q) use ($search){
            $q->where('name','like',"%$search%")
                ->orWhere('description','like',"%$search%");
        });
    }
}
