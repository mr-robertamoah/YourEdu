<?php

namespace App\YourEdu;

use App\Traits\AccountCommissionTrait;
use App\Traits\AccountFilesTrait;
use App\Traits\AccountQuestionsTrait;
use App\Traits\AccountTrait;
use App\Traits\FacilitatingAccountsTrait;
use App\Traits\AccountSalariesTrait;
use App\Traits\HasAnsweredbyTrait;
use App\Traits\HasFollowsTrait;
use App\Traits\HasWorkbyTrait;
use App\Traits\HasMarkedbyTrait;
use App\Traits\HasTimerAddedbyTrait;
use App\User;
use Database\Factories\FacilitatorFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Facilitator extends Model
{
    use SoftDeletes,
        AccountTrait,
        HasFactory,
        FacilitatingAccountsTrait,
        AccountSalariesTrait,
        AccountCommissionTrait,
        AccountFilesTrait,
        AccountQuestionsTrait,
        HasMarkedbyTrait,
        HasWorkbyTrait,
        HasAnsweredbyTrait,
        HasFollowsTrait,
        HasTimerAddedbyTrait;

    protected $fillable = [
        'user_id', 'name'
    ];

    protected static function booted()
    {
        static::created(function ($facilitator) {
            $user = $facilitator->user;
            $facilitator->profile()->create([
                'user_id' => $user->id,
                'name' => $facilitator->name ? $facilitator->name : $user->name,
            ]);

            $facilitator->verification()->create();

            if ($user->email != '' || $user->email != null) {
                $facilitator->emails()->create([
                    'email' => $user->email
                ]);
            }

            $facilitator->posts()->create([
                'content' => 'this is my first post.'
            ]);

            $facilitator->point()->create([
                'user_id' => $facilitator->user_id
            ]);
        });
    }

    public function likings()
    {
        return $this->morphMany(Like::class, 'likedby');
    }

    public function verification()
    {
        return $this->morphOne(Verification::class, 'verifiable');
    }

    public function profile()
    {
        return $this->morphOne(Profile::class, 'profileable');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function paymentsMade()
    {
        return $this->morphMany(Payment::class, 'paidby');
    }

    public function paymentsMadeTo()
    {
        return $this->morphMany(Payment::class, 'paidto');
    }

    public function paidFor()
    {
        return $this->morphMany(Payment::class, 'for');
    }

    public function bans()
    {
        return $this->morphMany(Ban::class, 'bannable');
    }

    public function employed()
    {
        return $this->morphMany(Employment::class, 'employee');
    }

    public function schools()
    {
        return $this->morphToMany(School::class, 'schoolable', 'schoolables');
    }

    public function phoneNumbers()
    {
        return $this->morphMany(PhoneNumber::class, 'phoneable');
    }

    public function emails()
    {
        return $this->morphMany(Email::class, 'emailable');
    }

    public function ownedClasses()
    {
        return $this->morphMany(ClassModel::class, 'ownedby');
    }

    public function addedClasses()
    {
        return $this->morphMany(ClassModel::class, 'addedby');
    }

    public function addedPrograms()
    {
        return $this->morphMany(Program::class, 'addedby');
    }

    public function addedCourses()
    {
        return $this->morphMany(Course::class, 'addedby');
    }

    public function keywords()
    {
        return $this->morphMany(Keyword::class, 'keywordable');
    }

    public function prices()
    {
        return $this->morphMany(Price::class, 'ownedby');
    }

    public function objectives()
    {
        return $this->morphMany(Objective::class, 'objectiveby');
    }

    public function ownedLessons()
    {
        return $this->morphMany(Lesson::class, 'ownedby');
    }

    public function addedLessons()
    {
        return $this->morphMany(Lesson::class, 'addedby');
    }

    public function savesMade()
    {
        return $this->morphMany(Save::class, 'savedby');
    }

    public function activitiesAdded()
    {
        return $this->morphMany(Activity::class, 'addedby');
    }

    public function collaborations()
    {
        return $this->morphToMany(Collaboration::class, 'collaborationable', 'collabo')
            ->withPivot(['state'])->withTimestamps();
    }

    public function collabos()
    {
        return $this->morphMany(Collabo::class, 'collaborationable');
    }

    public function addedCurricula()
    {
        return $this->morphMany(Curriculum::class, 'curriculumable', 'curriculumables');
    }

    public function uniqueSubjectsAdded()
    {
        return $this->morphMany(Subject::class, 'addedby');
    }

    public function aliasesAdded()
    {
        return $this->morphMany(Alias::class, 'addedby');
    }

    public function addedExtracurriculums()
    {
        return $this->morphMany(Extracurriculum::class, 'addedby');
    }

    public function ownedExtracurriculums()
    {
        return $this->morphMany(Extracurriculum::class, 'ownedby');
    }

    public function extracurriculums()
    {
        return $this->morphToMany(Extracurriculum::class, 'extracurriculumable', 'extra')
            ->withPivot(['resource', 'activity'])->withTimestamps();
    }

    public function grades()
    {
        return $this->morphToMany(Grade::class, 'gradeable', 'gradeables');
    }

    public function uniqueGradesAdded()
    {
        return $this->morphMany(Grade::class, 'addedby');
    }

    public function participants()
    {
        return $this->morphMany(Participant::class, 'accountable');
    }

    public function uniqueProgramsAdded()
    {
        return $this->morphMany(Program::class, 'addedby');
    }

    public function ownedPrograms()
    {
        return $this->morphMany(Program::class, 'ownedby');
    }

    public function programs()
    {
        return $this->morphToMany(Program::class, 'programmable', 'programmables')
            ->withPivot(['activity']);
    }

    public function curricula()
    {
        return $this->morphToMany(Curriculum::class, 'curriculumable', 'curriculumables');
    }

    public function classes()
    {
        return $this->morphToMany(ClassModel::class, 'classable', 'classables', null, 'class_id')
            ->withPivot(['activity']);
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

    public function uniqueCoursesAdded()
    {
        return $this->morphMany(Course::class, 'addedby');
    }

    public function ownedCourses()
    {
        return $this->morphMany(Course::class, 'ownedby');
    }

    public function addedAssessments()
    {
        return $this->morphMany(Assessment::class, 'addedby');
    }

    public function requestsSent()
    {
        return $this->morphMany(Request::class, 'requestfrom');
    }

    public function requestsReceived()
    {
        return $this->morphMany(Request::class, 'requestto');
    }

    public function links()
    {
        return $this->morphMany(Link::class, 'addedby');
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

    public function sharesOwned()
    {
        return $this->morphMany(Share::class, 'ownedby');
    }

    public function point()
    {
        return $this->morphOne(Point::class, 'pointable');
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

    public function permissions()
    {
        return $this->morphMany(Permission::class, 'permitting');
    }

    public function booksAuthored()
    {
        return $this->morphMany(Book::class, 'authoredby');
    }

    public function booksAdded()
    {
        return $this->morphMany(Book::class, 'addedby');
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

    public function posts()
    {
        return $this->morphMany(Post::class, 'addedby');
    }

    public function attachments()
    {
        return $this->morphMany(PostAttachment::class, 'attachedby');
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

    public function addedCollaborations()
    {
        return $this->morphMany(Collaboration::class, 'addedby');
    }

    public function facilitationDetails()
    {
        return $this->morphMany(FacilitationDetail::class, 'accountable');
    }

    public function ownedDiscounts()
    {
        return $this->morphMany(Discount::class, 'ownedby');
    }

    public function addedDiscounts()
    {
        return $this->morphMany(Discount::class, 'addedby');
    }

    protected static function newFactory()
    {
        return FacilitatorFactory::new();
    }
}
