<?php

use App\Services\AdminService;
use App\User;
use App\YourEdu\AcademicYear;
use App\YourEdu\AcademicYearSection;
use App\YourEdu\Activity;
use App\YourEdu\Facilitator;
use App\YourEdu\Learner;
use App\YourEdu\ParentModel;
use App\YourEdu\Professional;
use App\YourEdu\Admin;
use App\YourEdu\Answer;
use App\YourEdu\Assessment;
use App\YourEdu\AssessmentSection;
use App\YourEdu\Audio;
use App\YourEdu\Ban;
use App\YourEdu\Book;
use App\YourEdu\Character;
use App\YourEdu\ClassModel;
use App\YourEdu\Collaboration;
use App\YourEdu\Comment;
use App\YourEdu\Conversation;
use App\YourEdu\Course;
use App\YourEdu\CourseSection;
use App\YourEdu\Discussion;
use App\YourEdu\Extracurriculum;
use App\YourEdu\Fee;
use App\YourEdu\File;
use App\YourEdu\Flag;
use App\YourEdu\Follow;
use App\YourEdu\Grade;
use App\YourEdu\Image;
use App\YourEdu\Keyword;
use App\YourEdu\Lesson;
use App\YourEdu\Like;
use App\YourEdu\Link;
use App\YourEdu\Mark;
use App\YourEdu\Message;
use App\YourEdu\Poem;
use App\YourEdu\Post;
use App\YourEdu\PostAttachment;
use App\YourEdu\Price;
use App\YourEdu\Program;
use App\YourEdu\Question;
use App\YourEdu\Read;
use App\YourEdu\Request;
use App\YourEdu\Save;
use App\YourEdu\School;
use App\YourEdu\Subject;
use App\YourEdu\Subscription;
use App\YourEdu\Video;
use App\YourEdu\Word;
use Illuminate\Container\Container;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

function class_basename_lower(Model | string $class)
{
    $string = '';
    if (is_string($class)) {
        $string = substr($class,strrpos($class,'\\') + 1);
    }
    
    if ($class instanceof Model) {        
        $string = class_basename($class);
    }

    return strtolower(substr($string,0,1)) . substr($string,1);
}

function getAccountClass($string)
{
    if (str_contains($string,'learner')) {
        return Learner::class;
    } else if (str_contains($string,'parent')) {
        return ParentModel::class;
    } else if (str_contains($string,'facilitator')) {
        return Facilitator::class;
    } else if (str_contains($string,'professional')) {
        return Professional::class;
    } else if (str_contains($string,'school')) {
        return School::class;
    } else if (str_contains($string,'admin')) {
        return Admin::class;
    } else if (str_contains($string,'post')) {
        return 'post';
    } else if ($string === 'school') {
        return 'school';
    } else if ($string === 'question') {
        return 'question';
    } else if ($string === 'riddle') {
        return 'riddle';
    } else if ($string === 'poem') {
        return 'poem';
    } else if ($string === 'activity') {
        return 'activity';
    } else if ($string === 'Lesson') {
        return 'lesson';
    } else if ($string === 'book') {
        return 'book';
    } else if ($string === 'answer') {
        return 'answer';
    } else if ($string === 'comment') {
        return 'comment';
    } else if ($string === 'coursesection') {
        return 'courseSection';
    }
    return '';
}

function getYourEduModel($accountText, $accountTextId): Model | null
{
    $account = null;
    if ($accountText === 'learner') {
        $account = Learner::find($accountTextId);
    } else if ($accountText === 'facilitator') {
        $account = Facilitator::find($accountTextId);
    } else if ($accountText === 'professional') {
        $account = Professional::find($accountTextId);
    } else if ($accountText === 'parent') {
        $account = ParentModel::find($accountTextId);
    } else if ($accountText === 'school') {
        $account = School::find($accountTextId);
    } else if ($accountText === 'admin') {
        $account = Admin::find($accountTextId);
    } else if ($accountText === 'post') {
        $account = Post::find($accountTextId);
    } else if ($accountText === 'comment') {
        $account = Comment::find($accountTextId);
    } else if ($accountText === 'class') {
        $account = ClassModel::find($accountTextId);
    } else if ($accountText === 'lesson') {
        $account = Lesson::find($accountTextId);
    } else if ($accountText === 'discussion') {
        $account = Discussion::find($accountTextId);
    } else if ($accountText === 'read') {
        $account = Read::find($accountTextId);
    } else if ($accountText === 'answer') {
        $account = Answer::find($accountTextId);
    } else if ($accountText === 'poem') {
        $account = Poem::find($accountTextId);
    } else if ($accountText === 'activity') {
        $account = Activity::find($accountTextId);
    } else if ($accountText === 'book') {
        $account = Book::find($accountTextId);
    } else if ($accountText === 'program') {
        $account = Program::find($accountTextId);
    } else if ($accountText === 'courseSection') {
        $account = CourseSection::find($accountTextId);
    } else if ($accountText === 'course') {
        $account = Course::find($accountTextId);
    } else if ($accountText === 'grade') {
        $account = Grade::find($accountTextId);
    } else if ($accountText === 'subject') {
        $account = Subject::find($accountTextId);
    } else if ($accountText === 'conversation') {
        $account = Conversation::find($accountTextId);
    } else if ($accountText === 'message') {
        $account = Message::find($accountTextId);
    } else if ($accountText === 'question') {
        $account = Question::find($accountTextId);
    } else if ($accountText === 'follow') {
        $account = Follow::find($accountTextId);
    } else if ($accountText === 'request') {
        $account = Request::find($accountTextId);
    } else if ($accountText === 'mark') {
        $account = Mark::find($accountTextId);
    } else if ($accountText === 'word') {
        $account = Word::find($accountTextId);
    } else if ($accountText === 'keyword') {
        $account = Keyword::find($accountTextId);
    } else if ($accountText === 'character') {
        $account = Character::find($accountTextId);
    } else if ($accountText === 'like') {
        $account = Like::find($accountTextId);
    } else if ($accountText === 'flag') {
        $account = Flag::find($accountTextId);
    } else if ($accountText === 'save') {
        $account = Save::find($accountTextId);
    } else if ($accountText === 'postattachment') {
        $account = PostAttachment::find($accountTextId);
    } else if ($accountText === 'file') {
        $account = File::find($accountTextId);
    } else if ($accountText === 'image') {
        $account = Image::find($accountTextId);
    } else if ($accountText === 'video') {
        $account = Video::find($accountTextId);
    } else if ($accountText === 'audio') {
        $account = Audio::find($accountTextId);
    } else if ($accountText === 'collaboration') {
        $account = Collaboration::find($accountTextId);
    } else if ($accountText === 'extracurriculum') {
        $account = Extracurriculum::find($accountTextId);
    } else if ($accountText === 'user') {
        $account = User::find($accountTextId);
    } else if ($accountText === 'academicYear') {
        $account = AcademicYear::find($accountTextId);
    } else if ($accountText === 'academicYearSection') {
        $account = AcademicYearSection::find($accountTextId);
    } else if ($accountText === 'price') {
        $account = Price::find($accountTextId);
    } else if ($accountText === 'fee') {
        $account = Fee::find($accountTextId);
    } else if ($accountText === 'subscription') {
        $account = Subscription::find($accountTextId);
    } else if ($accountText === 'ban') {
        $account = Ban::find($accountTextId);
    } else if ($accountText === 'link') {
        $account = Link::find($accountTextId);
    } else if ($accountText === 'collaboration') {
        $account = Collaboration::find($accountTextId);
    } else if ($accountText === 'assessment') {
        $account = Assessment::find($accountTextId);
    } else if ($accountText === 'assessmentSection') {
        $account = AssessmentSection::find($accountTextId);
    }

    return $account;
}

function isOwnedBy($ownedby,$userId)
{
    if ($ownedby) {
        if ($ownedby->user_id === $userId ||
            $ownedby->owner_id === $userId) {
            return true;
        } else if (class_basename_lower($ownedby) === 'school' && 
            in_array($userId,AdminService::getAdminIds($ownedby))) {
            return true;
        } else {
            return false;
        }
    } else {
        return true;
    }
}

function capitalize(string $value)
{
    return strtoupper(substr($value,0,1)) . strtolower(substr($value,1));
}

function paginate(Collection $collection,$pageSize){
    $page = Paginator::resolveCurrentPage('page');
    $total = $collection->count();
    // dd($collection->forPage($page,$pageSize));

    return paginator($collection->forPage($page,$pageSize),$total,$pageSize,$page,[
        'path' => Paginator::resolveCurrentPath(),
        'pageName' => 'page'
    ]);
}

function paginator($items, $total, $perPage, $currentPage, $options){
    return Container::getInstance()->makeWith(LengthAwarePaginator::class,compact('items',
        'total', 'perPage','currentPage','options'));
}

?>