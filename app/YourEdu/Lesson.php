<?php

namespace App\YourEdu;

use App\Contracts\DashboardItemContract;
use App\Traits\AssessmentTrait;
use App\Traits\DashboardItemTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lesson extends DashboardItemContract
{
    //
    use SoftDeletes, DashboardItemTrait, AssessmentTrait;

    protected $fillable = [
        'title', 'description', 'ageGroup', 'state'
    ];

    public function addedby()
    {
        return $this->morphTo();
    }

    public function ownedby()
    {
        return $this->morphTo();
    }

    public function lessonable()
    {
        return $this->morphTo();
    }

    public function acitvities(){
        return $this->morphMany(Activity::class,'activityfor');
    }

    public function likes(){
        return $this->morphMany(Like::class,'likeable');
    }

    public function objectives()
    {
        return $this->morphMany(Objective::class,'objectiveable');
    }

    public function beenSaved()
    {
       return $this->morphMany(Save::class,'saveable');
    }

    public function books()
    {
        return $this->morphMany(Book::class,'bookable');
    }

    public function poems()
    {
        return $this->morphMany(Poem::class,'poemable');
    }

    public function riddles()
    {
        return $this->morphMany(Riddle::class,'riddleable');
    }

    public function summary()
    {
        return $this->morphOne(Summary::class,'summariable');
    }

    public function lessonRequirements()
    {
        return $this->hasMany(LessonRequirement::class);
    }

    public function curriculumable()
    {
        return $this->morphTo();
    }

    public function previousLesson()
    {
        return $this->belongsTo(Lesson::class,'previous_lesson_id');
    }

    public function nextLesson()
    {
        return $this->hasOne(Lesson::class,'previous_lesson_id');
    }

    public function curriculumStructure()
    {
        return $this->belongsTo(CurriculumStructure::class,'stucture_id');
    }

    public function courses()
    {
        return $this->morphToMany(Course::class,'coursable','coursables')
            ->withPivot(['activity'])->withTimestamps();
    }

    public function classSubjects()
    {
        return $this->classes->map(function($class) {
            return [
                'id' => $class->pivot->subject_id,
                'name' => Subject::find($class->pivot->subject_id)->name,
                'classId' => $class->id,
                'type' => 'subject',
            ];
        });
    }

    public function extracurriculums()
    {
        return $this->morphToMany(Extracurriculum::class,'extracurriculumable','extra')
            ->withPivot(['activity'])->withTimestamps();
    }

    public function courseSections()
    {
        return $this->morphToMany(CourseSection::class,'sectionable','sectionables',null,'section_id')
            ->withPivot(['lesson_number'])->withTimestamps();
    }

    public function classes()
    {
        return $this->morphToMany(ClassModel::class,'classable','classables',null,'class_id')
            ->withPivot(['activity','subject_id'])->withTimestamps();
    }

    public function programs()
    {
        return $this->morphToMany(Program::class,'programmable','programmables')
            ->withTimestamps();
    }

    public function subjects()
    {
        return $this->morphToMany(Subject::class,'subjectable','subjectables')
            ->withTimestamps();
    }

    public function grades()
    {
        return $this->morphToMany(Grade::class,'gradeable','gradeables')
            ->withTimestamps();
    }

    public function classSection()
    {
        return $this->belongsTo(ClassSection::class);
    }

    public function collaboration()
    {
        return $this->morphOne(Collaboration::class,'collaborationable');
    }

    public function assessments()
    {
        return $this->morphMany(Assessment::class,'assessmentable');
    }

    public function links()
    {
        return $this->morphMany(Link::class,'linkable');
    }

    public function files()
    {
        return $this->morphToMany(File::class,'fileable')
        ->withPivot(['state'])->withTimestamps();
    }

    public function audios()
    {
        return $this->morphToMany(Audio::class,'audioable')
        ->withPivot(['state'])->withTimestamps();
    }

    public function videos()
    {
        return $this->morphToMany(Video::class,'videoable')
        ->withPivot(['state'])->withTimestamps();
    }

    public function images()
    {
        return $this->morphToMany(Image::class,'imageable')
        ->withPivot(['state'])->withTimestamps();
    }

    public function comments()
    {
        return $this->morphMany(Comment::class,'commentable');
    }

    public function flags()
    {
        return $this->morphMany(Flag::class,'flaggable');
    }

    public function permissions()
    {
        return $this->morphMany(Permission::class,'permissible');
    }
    
    public function discussions()
    {
        return $this->morphMany(Discussion::class,'discussionfor');
    }
    
    public function payments()
    {
        return $this->morphMany(Payment::class,'what');
    }

    public function prices()
    {
        return $this->morphMany(Price::class,'priceable');
    }

    public function scopeSearchItems($query,$search)
    {
        return $query->where(function($q) use ($search){
            $q->where('title','like',"%$search%")
                ->orWhere('description','like',"%$search%");
        });
    }

    public function checkIfFreeOrIntro()
    {
        return $this->state === self::FREE || $this->state === self::INTRO;
    }
}
