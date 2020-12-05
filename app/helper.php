<?php

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
use App\YourEdu\Audio;
use App\YourEdu\Ban;
use App\YourEdu\Book;
use App\YourEdu\Character;
use App\YourEdu\ClassModel;
use App\YourEdu\Collaboration;
use App\YourEdu\Comment;
use App\YourEdu\Conversation;
use App\YourEdu\Course;
use App\YourEdu\Discussion;
use App\YourEdu\Extracurriculum;
use App\YourEdu\File;
use App\YourEdu\Flag;
use App\YourEdu\Follow;
use App\YourEdu\Grade;
use App\YourEdu\Image;
use App\YourEdu\Keyword;
use App\YourEdu\Lesson;
use App\YourEdu\Like;
use App\YourEdu\Mark;
use App\YourEdu\Message;
use App\YourEdu\Poem;
use App\YourEdu\Post;
use App\YourEdu\PostAttachment;
use App\YourEdu\Program;
use App\YourEdu\Question;
use App\YourEdu\Read;
use App\YourEdu\Request;
use App\YourEdu\Save;
use App\YourEdu\School;
use App\YourEdu\Subject;
use App\YourEdu\Video;
use App\YourEdu\Word;
use Illuminate\Container\Container;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

function getAccountString($classString)
{
    if (!is_string($classString)) {
        $classString = get_class($classString);
        if (!$classString) {
            return '';
        }
    }
    if (Str::contains(strtolower($classString), 'learner')) {
        return 'learner';
    } else if (Str::contains(strtolower($classString), 'parent')) {
        return 'parent';
    } else if (Str::contains(strtolower($classString), 'facilitator')) {
        return 'facilitator';
    } else if (Str::contains(strtolower($classString), 'professional')) {
        return 'professional';
    } else if (Str::contains(strtolower($classString), 'school')) {
        return 'school';
    } else if (Str::contains(strtolower($classString), 'admin')) {
        return 'admin';
    } else if (Str::contains(strtolower($classString), 'post')) {
        return 'post';
    } else if (Str::contains(strtolower($classString), 'question')) {
        return 'question';
    } else if (Str::contains(strtolower($classString), 'riddle')) {
        return 'riddle';
    } else if (Str::contains(strtolower($classString), 'poem')) {
        return 'poem';
    } else if (Str::contains(strtolower($classString), 'activity')) {
        return 'activity';
    } else if (Str::contains(strtolower($classString), 'Lesson')) {
        return 'lesson';
    } else if (Str::contains(strtolower($classString), 'book')) {
        return 'book';
    } else if (Str::contains(strtolower($classString), 'answer')) {
        return 'answer';
    } else if (Str::contains(strtolower($classString), 'comment')) {
        return 'comment';
    } else if (Str::contains(strtolower($classString), 'discussion')) {
        return 'discussion';
    } else if (Str::contains(strtolower($classString), 'character')) {
        return 'character';
    } else if (Str::contains(strtolower($classString), 'keyword')) {
        return 'keyword';
    } else if (Str::contains(strtolower($classString), 'word')) {
        return 'word';
    } else if (Str::contains(strtolower($classString), 'expression')) {
        return 'expression';
    } else if (Str::contains(strtolower($classString), 'image')) {
        return 'image';
    } else if (Str::contains(strtolower($classString), 'video')) {
        return 'video';
    } else if (Str::contains(strtolower($classString), 'audio')) {
        return 'audio';
    } else if (Str::contains(strtolower($classString), 'file')) {
        return 'file';
    } else if (Str::contains(strtolower($classString), 'collaboration')) {
        return 'collaboration';
    } else if (Str::contains(strtolower($classString), 'class')) {
        return 'class';
    } else if (Str::contains(strtolower($classString), 'extracurriculum')) {
        return 'extracurriculum';
    }
    return '';
}

function getAccountClass($string)
{
    if ($string === 'learner') {
        return Learner::class;
    } else if ($string === 'parent') {
        return ParentModel::class;
    } else if ($string === 'facilitator') {
        return Facilitator::class;
    } else if ($string === 'professional') {
        return Professional::class;
    } else if ($string === 'school') {
        return School::class;
    } else if ($string === 'admin') {
        return Admin::class;
    } else if ($string === 'post') {
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
    }
    return '';
}

function getAccountObject($accountText, $accountTextId)
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
    } else if ($accountText === 'ban') {
        $account = Ban::find($accountTextId);
    }

    return $account;
}
    
function getAdminIds(School $school)
{
    $fromUserIds = $school->admins->pluck('user_id')->toArray();
    if (!in_array($school->owner->id,$fromUserIds)) {        
        $fromUserIds[] = $school->owner->id;
    }

    return $fromUserIds;
}

function uploadYourEduFiles($files)
{
   
    $uploadedFilePaths = [];
    foreach ($files as $file) {
        $uploadedFilePaths[] = uploadYourEduFile($file);
    }
    return $uploadedFilePaths;
}

function deleteYourEduFiles($item)
{
    if (!is_null($item)) {
        $files = $item->files;
        $files = $files->merge($item->audios);
        $files = $files->merge($item->videos);
        $files = $files->merge($item->images);
        
        foreach ($files as $file) {
            deleteYourEduFile($file);
        }
    }
}

function deleteYourEduFile($file)
{
    if (!is_null($file)) {
        Storage::delete($file->path);
        $file->delete();
    }
}

function deleteFile($fileId, $fileType)
{
    $file = null;
    if (Str::contains($fileType,'image')) {
        $file = getAccountObject('image', $fileId);
    } else if (Str::contains($fileType,'video')) {
        $file = getAccountObject('video', $fileId);
    } else if (Str::contains($fileType,'audio')) {
        $file = getAccountObject('audio', $fileId);
    } else if (Str::contains($fileType,'file')) {
        $file = getAccountObject('file', $fileId);
    }

    if (!is_null($file)) {
        Storage::delete($file->path);
        $file->delete();
    }
}

function getFileDetails($actualFile, $save = true)
{
    $fileArray['mime'] = $actualFile->getClientMimeType();
    $fileArray['name'] = $actualFile->getClientOriginalName();
    $fileArray['size'] = $actualFile->getSize();
    if ($save) {
        $fileArray['path'] = uploadYourEduFile($actualFile);
    }

    return $fileArray;
}

function imageResize($file, $save = true)
{
    $width = imagesx($file);
    $height = imagesy($file);
    $newWidth = 100;
    $newHeight = $newWidth/$width * $height;

    $newFile = imagescale($file,$newWidth,$newHeight);

    $fileArray['mime'] = $file->getClientMimeType();
    $fileArray['size'] = $file->getSize();
    if ($save) {
        $fileArray['path'] = uploadYourEduFile($newFile);
    }

    return $fileArray;
}

function accountCreateFile($account, $fileDetails, $associate = null)
{
    $file = null;
    if(Str::contains($fileDetails['mime'], 'image')){
        $file = $account->addedImages()->create($fileDetails);
        if($associate){
            $associate->images()->attach($file);
        }
    } else if(Str::contains($fileDetails['mime'], 'video')){
        $file = $account->addedVideos()->create($fileDetails);
        if($associate){
            $associate->videos()->attach($file);
        }
    } else if(Str::contains($fileDetails['mime'], 'audio')){
        $file = $account->addedAudio()->create($fileDetails);
        if($associate){
            $associate->audios()->attach($file);
        }
    } else {
        $file = $account->addedFiles()->create($fileDetails);
        if($associate){
            $associate->files()->attach($file);
        }
    }

    return $file;
}

// function createThumbnail($path, $width, $height)
// {
//     $img = Image::make($path)->resize($width, $height, function ($constraint) {
//         $constraint->aspectRatio();
//     });
//     $img->save($path);
// }

function saveFileWithDetails($file, $newFile)
{
    if ($file) {
        $originalFileName = $file->getClientOriginalName();
        $originalFileExtension = $file->getClientOriginalExtension();
        $fileNameOnly = pathinfo($originalFileName, PATHINFO_FILENAME);
        $fileName = Str::slug($fileNameOnly . time()) . "." . $originalFileExtension;

        $path = $newFile->save(public_path("assets/images/{$fileName}"));

        return $path;
    }
}

function uploadYourEduFile($file, $hasThumbnail = false)
{
    if ($file) {
        $originalFileName = $file->getClientOriginalName();
        $originalMime = $file->getClientMimeType();
        $originalFileExtension = $file->getClientOriginalExtension();
        $fileNameOnly = pathinfo($originalFileName, PATHINFO_FILENAME);
        $fileName = Str::slug($fileNameOnly . time()) . "." . $originalFileExtension;
        if ($hasThumbnail) {
            $fileNameSmall = Str::slug($fileNameOnly. 'small' . time()) . "." . $originalFileExtension;
            $fileNameMedium = Str::slug($fileNameOnly. 'medium' . time()) . "." . $originalFileExtension;
        }
        if (Str::contains($originalMime, 'image')) {
            $dir = 'images';
        } else if (Str::contains($originalMime, 'video')) {
            $dir = 'videos';
        } else if (Str::contains($originalMime, 'audio')) {
            $dir = 'audio';
        } else {
            $dir = 'files';
        }
        
        if ($hasThumbnail) {

            $paths = [];
            
            $paths['main'] = $file->storeAs($dir,$fileName);
            $paths['small'] = $file->storeAs($dir,$fileNameSmall);
            $paths['medium'] = $file->storeAs($dir,$fileNameMedium);

            return $paths;
        } else {

            $path = $file->storeAs($dir,$fileName);

            return $path;
        }
    }
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