<?php

namespace App\YourEdu;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Learner extends Model
{
    //

    use SoftDeletes;

    protected $fillable = [
        'user_id','name'
    ];

    protected static function booted()
    {
        static::created(function ($learner){
            $user = $learner->user;
            $learner->profile()->create([
                'user_id' => $user->id,
                'name' => $learner->name ? $learner->name : $user->full_name,
            ]);
            $learner->posts()->create([
                'content' => 'this is my first post.'
            ]);
        });
    }

    public function follows(){
        return $this->morphOne(Follow::class,'followable');
    }

    public function activities()
    {
        return $this->morphMany(Activity::class,'activityby');
    }

    public function followings(){
        return $this->morphOne(Follow::class,'followedby');
    }

    public function profile(){
        return $this->morphOne(Profile::class,'profileable');
    }

    public function likings(){
        return $this->morphOne(Like::class,'likedby');
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function parents(){
        return $this->
            belongsToMany(ParentModel::class,'learner_parent','learner_id','parent_id')
            ->withPivot(['level','role'])->withTimestamps();
    }

    public function paidFor()
    {
        return $this->morphMany(Payment::class,'for');
    }

    public function phoneNumbers()
    {
        return $this->morphMany(PhoneNumber::class,'phoneable');
    }

    public function keywords()
    {
        return $this->morphMany(Keyword::class,'keywordable');
    }

    public function classes()
    {
        return $this->belongsToMany(ClassModel::class,'class_learner','learner_id','class_id')
                ->withPivot('type')->withTimestamps();
    }

    public function schools()
    {
        return $this->belongsToMany(School::class,'learner_school')
                ->withPivot('type')->withTimestamps();
    }
    
    public function works()
    {
        return $this->morphMany(Work::class,'workable');
    }
    
    public function answers()
    {
        return $this->morphMany(Answer::class,'answerable');
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function reportDetails()
    {
        return $this->hasMany(ReportDetail::class);
    }

    public function totalDetails()
    {
        return $this->hasMany(TotalDetail::class);
    }

    public function admissions()
    {
        return $this->hasMany(Admission::class);
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

    public function flags()
    {
        return $this->morphMany(Flag::class,'flaggable');
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
    

}
