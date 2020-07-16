<?php

namespace App\YourEdu;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Professional extends Model
{
    //
    use SoftDeletes;

    protected $fillable = [
        'user_id','name', 'description', 'role'
    ];

    protected static function booted()
    {
        static::created(function ($professional){
            $user = $professional->user;
            $professional->profile()->create([
                'user_id' => $user->id,
                'name' => $professional->name ? $professional->name : $user->full_name,
            ]);
            $professional->verification()->create();

            if ($user->email != '' || $user->email != null) {
                $professional->emails()->create([
                    'email' => $user->email
                ]);
            }

            $professional->posts()->create([
                'content' => 'this is my first post.'
            ]);
        });
    }

    public function emails(){
        return $this->morphMany(Email::class,'emailable');
    }

    public function schools()
    {
        return $this->belongsToMany(School::class)
                ->withPivot('relationship','relationship_description')
                ->withTimestamps();
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

    public function verification(){
        return $this->morphOne(Verification::class,'verifiable');
    }

    public function activities()
    {
        return $this->morphMany(Activity::class,'activityby');
    }

    public function profile(){
        return $this->morphOne(Profile::class,'profileable');
    }

    public function user(){
        return $this->belongsTo(User::class);
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

    public function prices()
    {
        return $this->morphMany(Price::class,'ownedby');
    }

    public function objectives()
    {
        return $this->morphMany(Objective::class,'objectiveby');
    }

    public function phoneNumbers(){
        return $this->morphMany(PhoneNumber::class,'phoneable');
    }

    public function ownedLessons()
    {
        return $this->morphMany(Lesson::class,'ownedby');
    }

    public function deliveredLessons()
    {
        return $this->morphMany(Lesson::class,'lessonable');
    }

    public function collaborations()
    {
        return $this->morphToMany(Collaboration::class,'collaborationable','collabo');
    }

    public function ownedCollaborations()
    {
        return $this->morphMany(Collaboration::class,'collaborationable');
    }

    public function curricula()
    {
        return $this->morphMany(Curriculum::class,'curriculable');
    }

    public function addedExtracurriculums()
    {
        return $this->morphMany(Extracurriculum::class,'addedby');
    }

    public function extracurriculums()
    {
        return $this->morphMany(Extracurriculum::class,'extrable');
    }
    
    public function works()
    {
        return $this->morphMany(Work::class,'workable');
    }
    
    public function answers()
    {
        return $this->morphMany(Answer::class,'answerable');
    }

    public function marks()
    {
        return $this->morphMany(Mark::class,'markedby');
    }

    public function assessments()
    {
        return $this->morphMany(Assessment::class,'assessmentby');
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

    public function sharesOwned()
    {
        return $this->morphMany(Share::class,'ownedby');
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

    public function permissions()
    {
        return $this->morphMany(Permission::class,'permitting');
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

    public function postAttachments()
    {
        return $this->morphMany(PostAttachment::class,'attachedby');
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
