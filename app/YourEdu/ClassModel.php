<?php

namespace App\YourEdu;

use App\Traits\AssessmentTrait;
use App\Traits\DashboardItemTrait;
use App\Traits\FeeTrait;
use App\Traits\NotOwnedbyTrait;
use Database\Factories\ClassFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClassModel extends Model
{
    //
    use SoftDeletes, 
        NotOwnedbyTrait, 
        DashboardItemTrait, 
        AssessmentTrait,
        HasFactory,
        FeeTrait;

    protected $fillable = [
        'name','state','description','max_learners','structure'
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
        return $this->morphToMany(Commission::class,'commissionable', 'commissionables');
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
        return $this->morphedByMany(Facilitator::class,'classable','classables','class_id')
            ->withTimestamps();
    }

    public function professionals(){
        return $this->morphedByMany(Facilitator::class,'classable','classables','class_id')
            ->withTimestamps();
    }

    public function learners(){
        return $this->morphedByMany(Learner::class,'classable','classables','class_id')
            ->withTimestamps();
    }

    public function schools(){
        return $this->morphedByMany(School::class,'classable','classables','class_id')
            ->withTimestamps();
    }

    public function classes(){
        return $this->morphedByMany(ClassModel::class,'classable','classables','class_id')
            ->withPivot(['resource'])->withTimestamps();
    }

    public function classSubjects(){
        return $this->morphedByMany(Subject::class,'classable','classables','class_id')
            ->withTimestamps();
    }

    public function courses(){
        return $this->morphedByMany(Course::class,'classable','classables','class_id')
            ->withTimestamps();
    }

    public function sections(){
        return $this->hasMany(ClassSection::class,'class_id');
    }

    public function prices()
    {
        return $this->morphMany(Price::class,'priceable');
    }

    public function subscriptions()
    {
        return $this->morphMany(Subscription::class,'subscribable');
    }

    public function collaboration()
    {
        return $this->morphOne(Collaboration::class,'collaborationable');
    }

    public function academicYears()
    {
        return $this->morphToMany(AcademicYear::class,'academicable','academicables',null,'academic_id');
    }
    
    public function payments()
    {
        return $this->morphMany(Payment::class,'what');
    }

    public function subjects()
    {
        return $this->morphToMany(Subject::class,'subjectable','subjectables')
                ->withPivot(['activity'])->withTimestamps();
    }

    public function programs()
    {
        return $this->morphToMany(Program::class,'programmable','programmables')
                ->withTimestamps();
    }

    public function extracurriculums()
    {
        return $this->morphedByMany(Extracurriculum::class,'classable','classables','class_id')
            ->withTimestamps();
    }

    public function gradingSystem(){
        return $this->belongsTo(GradingSystem::class,'grading_system_id');
    }

    public function lessons()
    {
        return $this->morphTOMany(Lesson::class,'lessonable','lessonables')
            ->withPivot(['type', 'lesson_number'])->withTimestamps();
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

    public function facilitationDetails()
    {
        return $this->morphMany(FacilitationDetail::class, 'itemable');
    }

    public function discussion()
    {
        return $this->discussions->first();
    }

    public function hasDiscussion()
    {
        return $this->discussions->count() > 0;
    }

    public function doesntHaveDiscussion()
    {
        return !$this->hasDiscussion();
    }

    public function specificFacilitationDetail($facilitatable, $accountable)
    {
        return $this->facilitationDetails()
            ->where('facilitatable_type', $facilitatable::class)
            ->where('facilitatable_id', $facilitatable->id)
            ->where('accountable_type', $accountable::class)
            ->where('accountable_id', $accountable->id)
            ->first();
    }

    public function facilitationDetailsAccountables()
    {
        return $this->facilitationDetails()
            ->has('accountable')->get()
            ->pluck('accounatable');
    }

    public function itemable()
    {
        return $this->morphMany(Lessonable::class, 'itemable');
    }

    public function assessments()
    {
        return $this->morphByMany(Assessment::class,'assessmentable');
    }

    public function assessmentable()
    {
        return $this->morphMany(Assessmentable::class,'itemable');
    }

    public function scopeRunningAcademicYears($query)
    {
        return $query->whereHas('academicYears',function($q) {
            $q->whereDate('start_date','<',now());
                // ->whereDate('end_date','>=',now());
        });
    }
    
    protected static function newFactory()
    {
        return ClassFactory::new();
    }
}
