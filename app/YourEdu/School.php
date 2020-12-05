<?php

namespace App\YourEdu;

use App\Traits\AccountTrait;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class School extends Model
{
    //

    use SoftDeletes, AccountTrait;

    protected $fillable = [
        'owner_id','company_name', 'role', 'class_structure'
    ];

    protected static function booted()
    {
        static::created(function ($school){
            $school->profile()->create([
                'user_id' => $school->owner_id,
                'name' => $school->company_name ,
            ]);
            $school->verification()->create();

            $school->point()->create([
                'user_id' => $school->owner_id
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

    public function bans()
    {
       return $this->morphMany(Ban::class,'bannable');
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

    public function ownedClasses()
    {
        return $this->morphMany(ClassModel::class,'ownedby');
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

    public function activityTrack()
    {
       return $this->morphOne(ActivityTrack::class,'for');
    }

    public function addedClasses()
    {
        return $this->morphMany(ClassModel::class,'addedby');
    }

    public function academicYears()
    {
        return $this->hasMany(AcademicYear::class);
    }

    public function academicYearSections()
    {
        return $this->hasMany(AcademicYearSection::class);
    }

    public function learners()
    {
        return $this->morphedByMany(Learner::class,'schoolable','schoolables');
    }

    public function parents()
    {
        return $this->morphedByMany(ParentModel::class,'schoolable','schoolables');
    }

    public function facilitators()
    {
        return $this->morphedByMany(Facilitator::class,'schoolable','schoolables');
    }

    public function professionals()
    {
        return $this->morphedByMany(Professional::class,'schoolable','schoolables');
    }

    public function addedCurricula()
    {
        return $this->morphMany(Curriculum::class,'curriculable');
    }

    // public function curriculaInUse()
    // {
    //     return $this->morphToMany(Curriculum::class,'curriculumable','curriculumables')
    //             ->withTimestamps();
    // }

    public function groupsOwned()
    {
        return $this->morphMany(Group::class,'ownedby');
    }

    public function uniqueProgramsAdded()
    {
        return $this->morphMany(Program::class,'addedby');
    }

    public function uniqueCoursesAdded()
    {
        return $this->morphMany(Course::class,'addedby');
    }

    public function uniqueSubjectsAdded()
    {
        return $this->morphMany(Subject::class,'addedby');
    }
    
    public function uniqueGradesAdded()
    {
        return $this->morphMany(Grade::class,'addedby');
    }

    public function aliasesAdded()
    {
        return $this->morphMany(Alias::class,'addedby');
    }

    public function addedExtracurriculums()
    {
        return $this->morphMany(Extracurriculum::class,'addedby');
    }

    public function extracurriculums()
    {
        return $this->morphToMany(Extracurriculum::class,'extracurriculumable','extra');
    }

    public function programs()
    {
        return $this->morphToMany(Program::class,'programmable','programmables');
    }

    // public function classes(){
    //     return $this->morphToMany(ClassModel::class,'classable','classables');
    // }

    public function curricula()
    {
        return $this->morphToMany(Curriculum::class,'curriculumable','curriculumables');
    }

    public function courses()
    {
        return $this->morphToMany(Course::class,'coursable','coursables')
            ->withPivot(['activity']);
    }

    public function subjects()
    {
        return $this->morphToMany(subject::class,'subjectable','subjectables')
            ->withPivot(['activity']);
    }

    public function fees()
    {
        return $this->hasMany(Fee::class);
    }

    public function grades(){
        return $this->morphToMany(Grade::class,'gradeable','gradeables');
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

    public function flagsRaised()
    {
        return $this->morphMany(Flag::class,'flaggedby');
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

    public function discussions()
    {
        return $this->morphMany(Discussion::class,'raisedby');
    }

    public function posts()
    {
        return $this->morphMany(Post::class,'postedby');
    }

    public function attachments()
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

    public function lessonsAdded()
    {
        return $this->morphMany(Lesson::class,'addedby');
    }

    public function lessonsOwned()
    {
        return $this->morphMany(Lesson::class,'ownedby');
    }

    public function conversations()
    {
        return $this->morphToMany(Conversation::class,'accountable','conversationables');
    }

    public function conversationAccounts()
    {
        return $this->morphMany(ConversationAccount::class,'accountable');
    }

    public function employments()
    {
        return $this->morphMany(Employment::class,'employer');
    }

    public function messagesSent()
    {
        return $this->morphMany(Message::class,'fromable');
    }

    public function messagesReceived()
    {
        return $this->morphMany(Message::class,'toable');
    }

    public function scopeHasMyAdmin($query,$id)
    {
        return $query->whereHas('admins',function($query) use ($id){
            $query->where('user_id',$id);
        })->with(['admins'=> function($query) use ($id){
            $query->where('user_id',$id);
        }]);
    }

    
}
