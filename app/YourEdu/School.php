<?php

namespace App\YourEdu;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class School extends Model
{
    //

    use SoftDeletes;

    protected $fillable = [
        'owner_id','company_name', 'role'
    ];

    protected static function booted()
    {
        static::created(function ($school){
            $user = $school->user;
            $school->profile()->create([
                'user_id' => $school->owner_id,
                'name' => $school->company_name ,
            ]);
            $school->verification()->create();

            $school->point()->create([
                'user_id' => $school->user_id
            ]);
        });
    }

    public function follows(){
        return $this->morphMany(Follow::class,'followable');
    }

    public function followings(){
        return $this->morphMany(Follow::class,'followedby');
    }

    public function likings(){
        return $this->morphMany(Like::class,'likedby');
    }

    public function likes(){
        return $this->morphMany(Like::class,'likeable');
    }

    public function verification(){
        return $this->morphOne(Verification::class,'verifiable');
    }

    public function profile(){
        return $this->morphOne(Profile::class,'profileable');
    }

    public function owner(){
        return $this->belongsTo(User::class,'owner_id');
    }

    public function admins()
    {
        return $this->belongsToMany(Admin::class)
            ->withPivot('level')
            ->withTimestamps();
    }

    public function paymentsMade()
    {
        return $this->morphMany(Payment::class,'paidby');
    }

    public function paymentsMadeTo()
    {
        return $this->morphMany(Payment::class,'paidto');
    }

    public function paidFor()
    {
        return $this->morphMany(Payment::class,'for');
    }

    public function facilitators()
    {
        return $this->belongsToMany(Facilitator::class)
                ->withPivot('relationship','relationship_description')
                ->withTimestamps();
    }

    public function professionals()
    {
        return $this->belongsToMany(Professional::class)
                ->withPivot('relationship','relationship_description')
                ->withTimestamps();
    }
    
    public function answers()
    {
        return $this->morphMany(Answer::class,'answeredby');
    }

    public function savesMade()
    {
       return $this->morphMany(Save::class,'savedby');
    }

    public function point()
    {
        return $this->morphOne(Point::class,'pointable');
    }

    public function phoneNumbers()
    {
        return $this->morphMany(PhoneNumber::class,'phoneable');
    }

    public function emails(){
        return $this->morphMany(Email::class,'emailable');
    }

    public function classes()
    {
        return $this->morphMany(ClassModel::class,'classable');
    }

    public function prices()
    {
        return $this->morphMany(Price::class,'ownedby');
    }

    public function objectives()
    {
        return $this->morphMany(Objective::class,'objectiveby');
    }

    public function ownedLessons()
    {
        return $this->morphMany(Lesson::class,'ownedby');
    }

    public function ownedCollaborations()
    {
        return $this->morphMany(Collaboration::class,'collaborationable');
    }

    public function academicYears()
    {
        return $this->hasMany(AcademicYear::class);
    }

    public function learners()
    {
        return $this->belongsToMany(Learner::class,'learner_school')
                ->withPivot('type')->withTimestamps();
    }

    public function curricula()
    {
        return $this->morphMany(Curriculum::class,'curriculable');
    }

    public function curriculaInUse()
    {
        return $this->belongsToMany(Curriculum::class,'curriculum_school','school_id','curriculum_id')
                ->withTimestamps();
    }

    public function groupsOwned()
    {
        return $this->morphMany(Group::class,'ownedby');
    }

    public function uniqueSubjects()
    {
        return $this->morphMany(Subject::class,'subjectable');
    }

    public function uniqueSubjectsAdded()
    {
        return $this->morphMany(Subject::class,'addedby');
    }

    public function addedExtracurriculums()
    {
        return $this->morphMany(Extracurriculum::class,'addedby');
    }

    public function extracurriculums()
    {
        return $this->morphToMany(Extracurriculum::class,'extra','extracurricumable');
    }

    public function fees()
    {
        return $this->hasMany(Fee::class);
    }

    public function uniqueGrades(){
        return $this->morphMany(Grade::class,'gradable');
    }

    public function gradingSystems()
    {
        return $this->hasMany(GradingSystem::class);
    }

    public function marks()
    {
        return $this->morphMany(Mark::class,'markedby');
    }

    public function assessments()
    {
        return $this->morphMany(Assessment::class,'assessmentby');
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function reportSections()
    {
        return $this->hasMany(ReportSection::class);
    }

    public function admissionsSent()
    {
        return $this->morphMany(Admission::class,'admissionfrom');
    }

    public function admissionsReceived()
    {
        return $this->morphMany(Admission::class,'admissionto');
    }
    
    public function requestsSent()
    {
        return $this->morphMany(Request::class,'requestfrom');
    }
    
    public function requestsReceived()
    {
        return $this->morphMany(Request::class,'requestto');
    }
    
    public function ownedImages()
    {
        return $this->morphMany(Image::class,'ownedby');
    }
    
    public function ownedFiles()
    {
        return $this->morphMany(File::class,'ownedby');
    }
    
    public function ownedVideos()
    {
        return $this->morphMany(Video::class,'ownedby');
    }
    
    public function ownedAudio()
    {
        return $this->morphMany(Audio::class,'ownedby');
    }
    
    public function addedImages()
    {
        return $this->morphMany(Image::class,'addedby');
    }
    
    public function addedFiles()
    {
        return $this->morphMany(File::class,'addedby');
    }
    
    public function addedVideos()
    {
        return $this->morphMany(Video::class,'addedby');
    }
    
    public function addedAudio()
    {
        return $this->morphMany(Audio::class,'addedby');
    }

    public function links()
    {
        return $this->morphMany(Link::class,'addedby');
    }

    public function sharesOwned()
    {
        return $this->morphMany(Share::class,'ownedby');
    }

    public function comments()
    {
        return $this->morphMany(Comment::class,'commentable');
    }

    public function commentsMade()
    {
        return $this->morphMany(Comment::class,'commentedby');
    }

    public function flags()
    {
        return $this->morphMany(Flag::class,'flaggable');
    }

    public function permissions()
    {
        return $this->morphMany(Permission::class,'permitting');
    }

    public function booksAdded()
    {
        return $this->morphMany(Book::class,'addedby');
    }

    public function readsStarted()
    {
        return $this->morphMany(Read::class,'startedby');
    }

    public function wordsAdded()
    {
        return $this->morphMany(Word::class,'wordable');
    }

    public function poemsAdded()
    {
        return $this->morphMany(Poem::class,'addedby');
    }

    public function activities()
    {
        return $this->morphMany(Activity::class,'activityby');
    }

    public function riddlesAdded()
    {
        return $this->morphMany(Riddle::class,'addedby');
    }

    public function posts()
    {
        return $this->morphMany(Post::class,'postedby');
    }

    public function postAttachments()
    {
        return $this->morphMany(PostAttachment::class,'attachedby');
    }
    
    public function contributionsMarked()
    {
        return $this->morphMany(Discussion::class,'markedby');
    }

    public function expressionsAdded()
    {
        return $this->morphMany(Expression::class,'expressionable');
    }

    public function charactersAdded()
    {
        return $this->morphMany(Character::class,'characterable');
    }

    public function questionsOwned()
    {
        return $this->morphMany(Question::class,'owned');
    }

    public function activitiesOwned()
    {
        return $this->morphMany(Activity::class,'owned');
    }

    
}
