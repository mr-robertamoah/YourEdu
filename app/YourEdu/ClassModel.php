<?php

namespace App\YourEdu;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClassModel extends Model
{
    //
    use SoftDeletes;

    protected $fillable = [
        'name','state','description','academic_id','max_learners','structure'
    ];

    public function profile(){
        return $this->morphOne(Profile::class,'profileable');
    }

    public function ownedby(){
        return $this->morphTo();
    }

    public function addedby(){
        return $this->morphTo();
    }

    public function commissions()
    {
        return $this->morphMany(Commission::class,'for');
    }

    public function activityTrack()
    {
       return $this->morphOne(ActivityTrack::class,'what');
    }

    public function curricula(){
        return $this->morphToMany(Curriculum::class,'curriculumable','curriculumables');
    }

    public function grades(){
        return $this->morphToMany(Grade::class,'gradeable','gradeables');
    }

    public function facilitators(){
        return $this->morphedByMany(Facilitator::class,'classable','classables','class_id');
    }

    public function learners(){
        return $this->morphedByMany(Learner::class,'classable','classables','class_id');
    }

    public function schools(){
        return $this->morphedByMany(School::class,'classable','classables','class_id');
    }

    public function sections(){
        return $this->hasMany(ClassSection::class,'class_id');
    }

    public function prices()
    {
        return $this->morphMany(Price::class,'priceable');
    }

    public function collaboration()
    {
        return $this->morphOne(Collaboration::class,'collaborationable');
    }

    public function academicYear()
    {
        return $this->morphToMany(AcademicYear::class,'academicable','academicables',null,'academic_id');
    }

    // public function uniqueSubjects()
    // {
    //     return $this->morphMany(Subject::class,'subjectable');
    // }

    public function subjects()
    {
        return $this->morphToMany(Subject::class,'subjectable','subjectables')
                ->withTimestamps();
    }

    public function extracurriculums()
    {
        return $this->morphToMany(Extracurriculum::class,'extracurriculumable','extra');
    }

    public function fees()
    {
        return $this->hasMany(Fee::class,'class_id');
    }

    public function gradingSystem(){
        return $this->belongsTo(GradingSystem::class,'grading_system_id');
    }

    public function lessons()
    {
        return $this->morphMany(Lesson::class,'lessonable');
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
        return $this->morphMany(Discussion::class,'discussionfor');
    }
}
