<?php

namespace App\YourEdu;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ParentModel extends Model
{
    //
    use SoftDeletes;

    protected $fillable = [
        'user_id','name',
    ];

    protected static function booted()
    {
        static::created(function ($parent){
            $user = $parent->user;
            $parent->profile()->create([
                'user_id' => $user->id,
                'name' => $parent->name ? $parent->name : $user->full_name,
            ]);
            $parent->verification()->create();
            $parent->posts()->create([
                'content' => 'this is my first post.'
            ]);

            $parent->point()->create([
                'user_id' => $parent->user_id
            ]);
        });
    }

    public function verification(){
        return $this->morphOne(Verification::class,'verifiable');
    }

    public function activitiesAdded()
    {
        return $this->morphMany(Activity::class,'activityby');
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

    public function profile(){
        return $this->morphOne(Profile::class,'profileable');
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function learners(){
        return $this->
            belongsToMany(Learner::class,'learner_parent','parent_id','learner_id')
            ->withPivot(['level','role'])->withTimestamps();
    }
    
    public function answers()
    {
        return $this->morphMany(Answer::class,'answeredby');
    }

    public function paymentsMade()
    {
        return $this->morphMany(Payment::class,'paidby');
    }

    public function paidFor()
    {
        return $this->morphMany(Payment::class,'for');
    }

    public function phoneNumbers()
    {
        return $this->morphMany(PhoneNumber::class,'phoneable');
    }

    public function savesMade()
    {
       return $this->morphMany(Save::class,'savedby');
    }

    public function point()
    {
        return $this->morphOne(Point::class,'pointable');
    }

    public function emails(){
        return $this->morphMany(Email::class,'emailable');
    }

    public function keywords()
    {
        return $this->morphMany(Keyword::class,'keywordable');
    }

    public function objectives()
    {
        return $this->morphMany(Objective::class,'objectiveby');
    }

    public function marks()
    {
        return $this->morphMany(Mark::class,'markedby');
    }

    public function assessments()
    {
        return $this->morphMany(Assessment::class,'assessmentby');
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

    public function groupsOwned()
    {
        return $this->morphMany(Group::class,'ownedby');
    }

    public function groupsCreated()
    {
        return $this->morphMany(Group::class,'createdby');
    }

    public function groups()
    {
        return $this->morphToMany(Group::class,'groupable','groupables')
            // ->withPivot(['state','type','end_date'])
            ->withTimestamps();
    }

    public function comments()
    {
        return $this->morphMany(Comment::class,'commentedby');
    }

    public function flagsRaised()
    {
        return $this->morphMany(Flag::class,'flaggedby');
    }

    public function booksAuthored()
    {
        return $this->morphMany(Book::class,'authoredby');
    }

    public function booksAdded()
    {
        return $this->morphMany(Book::class,'addedby');
    }

    public function readsJoined()
    {
        return $this->morphToMany(Read::class,'readable','readables');
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

    public function poemsAuthored()
    {
        return $this->morphMany(Poem::class,'authoredby');
    }

    public function riddlesAdded()
    {
        return $this->morphMany(Riddle::class,'addedby');
    }

    public function questionsAdded()
    {
        return $this->morphMany(Question::class,'questionedby');
    }

    public function riddlesAuthored()
    {
        return $this->morphMany(Riddle::class,'authoredby');
    }

    public function posts()
    {
        return $this->morphMany(Post::class,'postedby');
    }

    public function expressionsAdded()
    {
        return $this->morphMany(Expression::class,'expressionable');
    }

    public function charactersAdded()
    {
        return $this->morphMany(Character::class,'characterable');
    }

    public function members()
    {
        return $this->morphMany(Member::class,'memberable');
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
}
