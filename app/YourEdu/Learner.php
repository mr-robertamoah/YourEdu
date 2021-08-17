<?php

namespace App\YourEdu;

use App\Traits\AccountQuestionsTrait;
use App\Traits\AccountTrait;
use App\Traits\AdmissionTrait;
use App\Traits\HasAnsweredbyTrait;
use App\Traits\HasFollowsTrait;
use App\Traits\HasMarkedbyTrait;
use App\Traits\HasTimerAddedbyTrait;
use App\Traits\HasWorkbyTrait;
use App\User;
use Database\Factories\LearnerFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Learner extends Model
{
    use SoftDeletes,
        AccountTrait,
        AdmissionTrait,
        HasFactory,
        AccountQuestionsTrait,
        HasWorkbyTrait,
        HasAnsweredbyTrait,
        HasFollowsTrait,
        HasTimerAddedbyTrait,
        HasMarkedbyTrait;

    const VALIDACCOUNTTYPE = [
        'learner', 'parent', 'professional', 'facilitator', 'school'
    ];

    protected $fillable = [
        'user_id', 'name'
    ];

    protected static function booted()
    {
        static::created(function ($learner) {
            $user = $learner->user;
            $learner->profile()->create([
                'user_id' => $user->id,
                'name' => $learner->name ? $learner->name : $user->name,
            ]);
            $learner->posts()->create([
                'content' => 'this is my first post.'
            ]);

            $learner->point()->create([
                'user_id' => $learner->user_id
            ]);
        });
    }

    public function participants()
    {
        return $this->morphMany(Participant::class, 'accountable');
    }

    public function activitiesAdded()
    {
        return $this->morphMany(Activity::class, 'addedby');
    }

    public function profile()
    {
        return $this->morphOne(Profile::class, 'profileable');
    }

    public function likings()
    {
        return $this->morphMany(Like::class, 'likedby');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function parents()
    {
        return $this->belongsToMany(ParentModel::class, 'learner_parent', 'learner_id', 'parent_id')
            ->withPivot(['level', 'role'])->withTimestamps();
    }

    public function paidFor()
    {
        return $this->morphMany(Payment::class, 'for');
    }

    public function bans()
    {
        return $this->morphMany(Ban::class, 'bannable');
    }

    public function phoneNumbers()
    {
        return $this->morphMany(PhoneNumber::class, 'phoneable');
    }

    public function emails()
    {
        return $this->morphMany(Email::class, 'emailable');
    }

    public function keywords()
    {
        return $this->morphMany(Keyword::class, 'keywordable');
    }

    public function schools()
    {
        return $this->morphToMany(School::class, 'schoolable', 'schoolables');
    }

    public function grades()
    {
        return $this->morphToMany(Grade::class, 'gradeable', 'gradeables')
            ->withTimestamps();
    }

    public function savesMade()
    {
        return $this->morphMany(Save::class, 'savedby');
    }

    public function point()
    {
        return $this->morphOne(Point::class, 'pointable');
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

    public function attachments()
    {
        return $this->morphMany(PostAttachment::class, 'attachedby');
    }

    public function extracurriculums()
    {
        return $this->morphToMany(Extracurriculum::class, 'extracurriculumable', 'extra')
            ->withPivot(['resource', 'activity'])->withTimestamps();
    }

    public function addedFees()
    {
        return $this->morphMany(Fee::class, 'addedby');
    }

    public function aliasesAdded()
    {
        return $this->morphMany(Alias::class, 'addedby');
    }

    public function programs()
    {
        return $this->morphToMany(Program::class, 'programmable', 'programmables')
            ->withTimestamps();
    }

    public function curricula()
    {
        return $this->morphToMany(Curriculum::class, 'curriculumable', 'curriculumables');
    }

    public function classes()
    {
        return $this->morphToMany(ClassModel::class, 'classable', 'classables', null, 'class_id');
    }

    public function courses()
    {
        return $this->morphToMany(Course::class, 'coursable', 'coursables')
            ->withPivot(['activity'])->withTimestamps();
    }

    public function subjects()
    {
        return $this->morphToMany(subject::class, 'subjectable', 'subjectables')
            ->withPivot(['activity'])->withTimestamps();
    }

    public function requestsSent()
    {
        return $this->morphMany(Request::class, 'requestfrom');
    }

    public function requestsReceived()
    {
        return $this->morphMany(Request::class, 'requestto');
    }

    public function ownedImages()
    {
        return $this->morphMany(Image::class, 'ownedby');
    }

    public function ownedFiles()
    {
        return $this->morphMany(File::class, 'ownedby');
    }

    public function ownedVideos()
    {
        return $this->morphMany(Video::class, 'ownedby');
    }

    public function ownedAudio()
    {
        return $this->morphMany(Audio::class, 'ownedby');
    }

    public function addedImages()
    {
        return $this->morphMany(Image::class, 'addedby');
    }

    public function addedFiles()
    {
        return $this->morphMany(File::class, 'addedby');
    }

    public function addedVideos()
    {
        return $this->morphMany(Video::class, 'addedby');
    }

    public function addedAudio()
    {
        return $this->morphMany(Audio::class, 'addedby');
    }

    public function groupsOwned()
    {
        return $this->morphMany(Group::class, 'ownedby');
    }

    public function groupsCreated()
    {
        return $this->morphMany(Group::class, 'createdby');
    }

    public function groups()
    {
        return $this->morphToMany(Group::class, 'groupable', 'groupables')
            // ->withPivot(['state','type','end_date'])
            ->withTimestamps();
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentedby');
    }

    public function flagsRaised()
    {
        return $this->morphMany(Flag::class, 'flaggedby');
    }

    public function flags()
    {
        return $this->morphMany(Flag::class, 'flaggable');
    }

    public function booksAuthored()
    {
        return $this->morphMany(Book::class, 'authoredby');
    }

    public function booksAdded()
    {
        return $this->morphMany(Book::class, 'addedby');
    }

    public function addedLessons()
    {
        return $this->morphMany(Lesson::class, 'addedby');
    }

    public function ownedLessons()
    {
        return $this->morphMany(Lesson::class, 'ownedby');
    }

    public function readsJoined()
    {
        return $this->morphToMany(Read::class, 'readable', 'readables');
    }

    public function readsStarted()
    {
        return $this->morphMany(Read::class, 'startedby');
    }

    public function wordsAdded()
    {
        return $this->morphMany(Word::class, 'wordable');
    }

    public function poemsAdded()
    {
        return $this->morphMany(Poem::class, 'addedby');
    }

    public function poemsAuthored()
    {
        return $this->morphMany(Poem::class, 'authoredby');
    }

    public function riddlesAdded()
    {
        return $this->morphMany(Riddle::class, 'addedby');
    }

    public function riddlesAuthored()
    {
        return $this->morphMany(Riddle::class, 'authoredby');
    }

    public function discussions()
    {
        return $this->morphMany(Discussion::class, 'raisedby');
    }

    public function addedAssessments()
    {
        return $this->morphMany(Assessment::class, 'addedby');
    }

    public function posts()
    {
        return $this->morphMany(Post::class, 'addedby');
    }

    public function expressionsAdded()
    {
        return $this->morphMany(Expression::class, 'expressionable');
    }

    public function charactersAdded()
    {
        return $this->morphMany(Character::class, 'characterable');
    }

    public function members()
    {
        return $this->morphMany(Member::class, 'memberable');
    }

    public function conversations()
    {
        return $this->morphToMany(Conversation::class, 'accountable', 'conversationables');
    }

    public function conversationAccounts()
    {
        return $this->morphMany(ConversationAccount::class, 'accountable');
    }

    public function messagesSent()
    {
        return $this->morphMany(Message::class, 'fromable');
    }

    public function messagesReceived()
    {
        return $this->morphMany(Message::class, 'toable');
    }

    public function ownedDiscounts()
    {
        return $this->morphMany(Discount::class, 'ownedby');
    }

    public function addedDiscounts()
    {
        return $this->morphMany(Discount::class, 'addedby');
    }

    public function hasParents()
    {
        return $this->parents()->count() > 0;
    }

    public function getParentUserIds()
    {
        return $this->parents->pluck('user_id');
    }

    public function scopeWhereUnderAged(Builder $query)
    {
        return $query->whereHas('user', function ($query) {
            $query
                ->whereDate('dob', '>', now()->subYears(User::MINIMUM_ADULT_AGE));
        });
    }

    public function scopeWhereHasNoAge($query)
    {
        return $query->whereHas('user', function ($query) {
            $query
                ->whereNull('dob');
        });
    }

    protected static function newFactory()
    {
        return LearnerFactory::new();
    }
}
