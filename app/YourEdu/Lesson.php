<?php

namespace App\YourEdu;

use App\Contracts\DashboardItemContract;
use App\Traits\AssessmentTrait;
use App\Traits\DashboardItemTrait;
use Database\Factories\LessonFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lesson extends DashboardItemContract
{
    //
    use SoftDeletes, DashboardItemTrait, AssessmentTrait, HasFactory;

    protected $fillable = [
        'title', 'description', 'age_group', 'state', 'published_at'
    ];

    protected $casts = [
        'published_at' => 'datetime'
    ];

    public function lessonables()
    {
        return $this->hasMany(Lessonable::class);
    }

    public function specificLessonable($lessonable, $itemable)
    {
        return $this->lessonables()
            ->where('lessonable_type', $lessonable::class)
            ->where('lessonable_id', $lessonable->id)
            ->where('itemable_type', $itemable::class)
            ->where('itemable_id', $itemable->id)
            ->first();
    }

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
        return $this->morphedByMany(Course::class,'lessonable','lessonables')
            ->withPivot(['type', 'lesson_number'])->withTimestamps();
    }

    public function extracurriculums()
    {
        return $this->morphedByMany(Extracurriculum::class,'lessonable','lessonables')
            ->withPivot(['type', 'lesson_number'])->withTimestamps();
    }

    public function courseSections()
    {
        return $this->morphedByMany(CourseSection::class,'lessonable','lessonables')
            ->withPivot(['lesson_number', 'type'])->withTimestamps();
    }

    public function classSubjects()
    {
        return $this->morphedByMany(Subject::class,'lessonable','lessonables')
            ->withPivot(['lesson_number', 'type'])->withTimestamps();
    }

    public function classes()
    {
        return $this->morphedByMany(ClassModel::class,'itemable','lessonables')
            ->withPivot(['lesson_number','type'])->withTimestamps();
    }

    public function programs()
    {
        return $this->morphedByMany(Program::class,'lessonable','lessonables')
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
        return $this->morphedByMany(Assessment::class,'assessmentable');
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

    public function discussion()
    {
        return $this->discussions->first();
    }
    
    public function payments()
    {
        return $this->morphMany(Payment::class,'what');
    }

    public function prices()
    {
        return $this->morphMany(Price::class,'priceable');
    }

    public function hasDiscussion()
    {
        return $this->discussions->count() > 0;
    }

    public function doesntHaveDiscussion()
    {
        return !$this->hasDiscussion();
    }

    public function checkIfFreeOrIntro()
    {
        return $this->state === self::FREE || $this->state === self::INTRO;
    }
    
    public function allFiles()
    {
        $files = $this->images;
        $files = $files->merge($this->videos);
        $files = $files->merge($this->audios);
        $files = $files->merge($this->files);

        return $files;
    }

    public function scopeWhereAddedOrCollaborator($query, $account)
    {
        return $query->where(function($query) use ($account) {
                $query->whereHasMorph('addedby', '*', function($query, $type) use ($account) {
                    if ($type === Collaboration::class) {
                        $query->whereCollaborationable($account);
                    }
                    if ($type !== Collaboration::class) {                            
                        $query->where('user_id', $account->user_id);
                    }
                });
            });
    }

    public function scopeSearchItems($query,$search)
    {
        return $query->where(function($q) use ($search){
            $q->where('title','like',"%$search%")
                ->orWhere('description','like',"%$search%");
        });
    }
    
    protected static function newFactory()
    {
        return LessonFactory::new();
    }
}
