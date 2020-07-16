<?php

namespace App\YourEdu;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClassModel extends Model
{
    //
    // use SoftDeletes;

    public function profile(){
        return $this->morphOne(Profile::class,'profileable');
    }

    public function classable(){
        return $this->morphTo();
    }

    public function curriculum(){
        return $this->belongsTo(Curriculum::class);
    }

    public function grade(){
        return $this->belongsTo(Grade::class);
    }

    public function facilitators(){
        return $this->belongsToMany(Facilitator::class,'class_facilitator','class_id','facilitator_id')
                ->withTimestamps();
    }

    public function sections(){
        return $this->hasMany(ClassSection::class,'class_id');
    }

    public function price()
    {
        return $this->morphOne(Price::class,'priceable');
    }

    public function collaboration()
    {
        return $this->morphOne(Collaboration::class,'collaborationable');
    }

    public function academicSections()
    {
        return $this->belongsToMany(AcademicYearSection::class,'academic_section_class','class_id','academic_year_section_id')
                ->withTimestamps();
    }

    public function learners()
    {
        return $this->belongsToMany(Learner::class,'class_learner','class_id')
                ->withPivot('type')->withTimestamps();
    }

    public function uniqueSubjects()
    {
        return $this->morphMany(Subject::class,'subjectable');
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class,'class_subject','class_id')
                ->withTimestamps();
    }

    public function extracurriculums()
    {
        return $this->morphToMany(Extracurriculum::class,'extra','extracurricumable');
    }

    public function fees()
    {
        return $this->hasMany(Fee::class);
    }

    public function gradingSystem(){
        return $this->belongsTo(GradingSystem::class,'grading_system_id');
    }

    public function reports()
    {
        return $this->hasMany(Report::class,'class_id');
    }

    public function reportSections()
    {
        return $this->hasMany(ReportSection::class,'class_id');
    }
    
    public function requests()
    {
        return $this->morphMany(Request::class,'requestable');
    }

    public function comments()
    {
        return $this->morphMany(Comment::class,'commentable');
    }

    public function permissions()
    {
        return $this->morphMany(Permission::class,'permissible');
    }
    
    public function discussions()
    {
        return $this->morphMany(Discussion::class,'discussionable');
    }
}
