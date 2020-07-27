<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProfileResource;
use App\User;
use App\helper;
use App\Http\Resources\CommentResource;
use App\Http\Resources\PostCollection;
use App\Http\Resources\PostResource;
use App\YourEdu\Admin;
use App\YourEdu\Admission;
use App\YourEdu\Ban;
use App\YourEdu\Character;
use App\YourEdu\ClassModel;
use App\YourEdu\Comment;
use App\YourEdu\Discussion;
use App\YourEdu\Facilitator;
use App\YourEdu\Flag;
use App\YourEdu\Image;
use App\YourEdu\Learner;
use App\YourEdu\Lesson;
use App\YourEdu\ParentModel;
use App\YourEdu\Post;
use App\YourEdu\Professional;
use App\YourEdu\Profile;
use App\YourEdu\Read;
use App\YourEdu\Request as YourEduRequest;
use App\YourEdu\School;
use Carbon\Carbon;
use Illuminate\Http\Request ;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class YourEduController extends Controller
{
    //

    public function getFileDetails($actualFile)
    {
        $file = $actualFile;
        $fileArray['mime'] = $file->getClientMimeType();
        $fileArray['size'] = $file->getSize();
        $fileArray['path'] = uploadYourEduFile($file);

        return $fileArray;
    }

    public function accountCreateFile(String $mime, $account, $fileDetails, $associate = null)
    {
        if(Str::contains($mime, 'image')){
            $file = $account->addedImages()->create($fileDetails);
            if($associate){
                $associate->images()->attach($file);
            }
        } else if(Str::contains($mime, 'video')){
            $file = $account->vidoes()->create($fileDetails);
            if($associate){
                $associate->addedVideos()->attach($file);
            }
        } else if(Str::contains($mime, 'audio')){
            $file = $account->addedAudios()->create($fileDetails);
            if($associate){
                $associate->audios()->attach($file);
            }
        } else if(Str::contains($mime, 'file')){
            $file = $account->addedFiles()->create($fileDetails);
            if($associate){
                $associate->files()->attach($file);
            }
        }
        // if($associate){
        //     $associate->save();
        // }
        return $file;
    }
//////////////////////////////////////////////////////////
    public function commentCreate(Request $request, $item, $itemId)
    {
        // return $request;
        $request->validate([
            'body' => 'nullable|string',
            'account' => 'required|string',
            'accountId' => 'required|string',
            'file' => 'nullable|file',
        ]);
        
        if(is_null($request->body) && is_null($request->file)){
            return response()->json([
                'message' => "unsuccessful. there is nothing to add as comment."
            ],422);
        }

        $comment = null;
        $account = null;
        $file = null;
        $accountId = $request->accountId;
        if ($request->account === 'learner') {
            $account = Learner::find($accountId);
        } else if ($request->account === 'parent') {
            $account = ParentModel::find($accountId);
        } else if ($request->account === 'facilitator') {
            $account = Facilitator::find($accountId);
        } else if ($request->account === 'professional') {
            $account = Professional::find($accountId);
        } else if ($request->account === 'admin') {
            $account = Admin::find($accountId);
        } else {
            return response()->json([
                'message' => "unsuccessful. {$request->account} does not exist."
            ],422);
        }

        if (!$account) {
            return response()->json([
                'message' => "unsuccessful. there is no such {$request->account}."
            ]);
        }

        if ($account->user_id !== auth()->id()) {
            return response()->json([
                'message' => "unsuccessful. you do not own this account."
            ]);
        }

        try {
            if ($account->user_id === Auth::id()) {
                DB::beginTransaction();
                $comment = $account->comments()->create([
                    'body' => $request->body
                ]);
                
                $file = null;
                if ($request->hasFile('file')) {
                    $fileDetails = [];
                    $fileDetails = $this->getFileDetails($request->file('file'));

                    $file = $this->accountCreateFile(
                        $fileDetails['mime'],
                        $account, 
                        $fileDetails,
                        $comment
                    );
                }
            } else {
                return response()->json([
                    'message' => "unsuccessful. {$request->account} does not exist 0r does not belong to you."
                ]);
            }
    
            if ($item === 'post') {
                $post = Post::find($itemId);
    
                if ($comment && $post) {
                    $comment->commentable()->associate($post);
                    $comment->save();
    
                    DB::commit();
                    return response()->json([
                        'message' => "successful",
                        'comment' => new CommentResource($comment),
                    ]);
                } else {
                    if($file){
                        Storage::delete($file->path);
                    }
                    DB::rollback();
                    return response()->json([
                        'message' => "unsuccessful. {$item} does not exist or comment was not created"
                    ]);
                }
            } else if ($item === 'comment') {
                $oldComment = Comment::find($itemId);

                if ($comment && $oldComment) {
                    $comment->commentable()->associate($oldComment);
                    $comment->save();
    
                    DB::commit();
                    return response()->json([
                        'message' => "successful",
                        'comment' => new CommentResource($comment),
                    ]);
                } else {
                    if($file){
                        Storage::delete($file->path);
                    }
                    DB::rollback();
                    return response()->json([
                        'message' => "unsuccessful. {$item} does not exist or comment was not created"
                    ]);
                }
            } else if ($item === 'lesson') {
                $lesson = Lesson::find($itemId);

                if ($comment && $lesson) {
                    $comment->commentable()->associate($lesson);
                    $comment->save();
    
                    DB::commit();
                    return response()->json([
                        'message' => "successful",
                        'comment' => new CommentResource($comment),
                    ]);
                } else {
                    if($file){
                        Storage::delete($file->path);
                    }
                    DB::rollback();
                    return response()->json([
                        'message' => "unsuccessful. {$item} does not exist or comment was not created"
                    ]);
                }
            } else if ($item === 'class') {
                $class = ClassModel::find($itemId);

                if ($comment && $class) {
                    $comment->commentable()->associate($class);
                    $comment->save();
    
                    DB::commit();
                    return response()->json([
                        'message' => "successful",
                        'comment' => new CommentResource($comment),
                    ]);
                } else {
                    if($file){
                        Storage::delete($file->path);
                    }
                    DB::rollback();
                    return response()->json([
                        'message' => "unsuccessful. {$item} does not exist or comment was not created"
                    ]);
                }
            } else if ($item === 'request') {
                $eduRequest = YourEduRequest::find($itemId);

                if ($comment && $eduRequest) {
                    $comment->commentable()->associate($eduRequest);
                    $comment->save();
    
                    DB::commit();
                    return response()->json([
                        'message' => "successful",
                        'comment' => new CommentResource($comment),
                    ]);
                } else {
                    if($file){
                        Storage::delete($file->path);
                    }
                    DB::rollback();
                    return response()->json([
                        'message' => "unsuccessful. {$item} does not exist or comment was not created"
                    ]);
                }
            } else if ($item === 'admission') {
                $admission = Admission::find($itemId);

                if ($comment && $admission) {
                    $comment->commentable()->associate($admission);
                    $comment->save();
    
                    DB::commit();
                    return response()->json([
                        'message' => "successful",
                        'comment' => new CommentResource($comment),
                    ]);
                } else {
                    if($file){
                        Storage::delete($file->path);
                    }
                    DB::rollback();
                    return response()->json([
                        'message' => "unsuccessful. {$item} does not exist or comment was not created"
                    ]);
                }
            } else if ($item === 'ban') {
                $ban = Ban::find($itemId);

                if ($comment && $ban) {
                    $comment->commentable()->associate($ban);
                    $comment->save();
    
                    DB::commit();
                    return response()->json([
                        'message' => "successful",
                        'comment' => new CommentResource($comment),
                    ]);
                } else {
                    if($file){
                        Storage::delete($file->path);
                    }
                    DB::rollback();
                    return response()->json([
                        'message' => "unsuccessful. {$item} does not exist or comment was not created"
                    ]);
                }
            } else if ($item === 'flag') {
                $flag = Flag::find($itemId);

                if ($comment && $flag) {
                    $comment->commentable()->associate($flag);
                    $comment->save();
    
                    DB::commit();
                    return response()->json([
                        'message' => "successful",
                        'comment' => new CommentResource($comment),
                    ]);
                } else {
                    if($file){
                        Storage::delete($file->path);
                    }
                    DB::rollback();
                    return response()->json([
                        'message' => "unsuccessful. {$item} does not exist or comment was not created"
                    ]);
                }
            } else if ($item === 'keyword') {
    
            } else if ($item === 'word') {
    
            } else if ($item === 'expression') {
                
            } else if ($item === 'character') {
                $character = Character::find($itemId);

                if ($comment && $character) {
                    $comment->commentable()->associate($character);
                    $comment->save();
    
                    DB::commit();
                    return response()->json([
                        'message' => "successful",
                        'comment' => new CommentResource($comment),
                    ]);
                } else {
                    if($file){
                        Storage::delete($file->path);
                    }
                    DB::rollback();
                    return response()->json([
                        'message' => "unsuccessful. {$item} does not exist or comment was not created"
                    ]);
                }
            } else if ($item === 'school') {
                $school = School::find($itemId);

                if ($comment && $school) {
                    $comment->commentable()->associate($school);
                    $comment->save();
    
                    DB::commit();
                    return response()->json([
                        'message' => "successful",
                        'comment' => new CommentResource($comment),
                    ]);
                } else {
                    if($file){
                        Storage::delete($file->path);
                    }
                    DB::rollback();
                    return response()->json([
                        'message' => "unsuccessful. {$item} does not exist or comment was not created"
                    ]);
                }
            }
        } catch (\Throwable $th) {
            if($file){
                Storage::delete($file->path);
            }
            DB::rollback();
            // return response()->json([
            //     'message' => "Unsuccessful. Something might have gone wrong. Please try again later."
            // ]);
            throw $th;
        }
        
    }

    public function commentEdit(Request $request, $comment)
    {
        return $comment;
    }

    public function commentDelete($comment)
    {
        // return $comment;
        $mainComment = Comment::find($comment);
        
        try {
            $mainComment->delete();
            return response()->json([
                'message' => "successful"
            ]);
        } catch (\Throwable $th) {
            throw $th;
            return response()->json([
                'message' => "unsuccessful"
            ]);
        }
    }

    public function commentsGet(Request $request, $item, $itemId)
    {
        $mainItem = null;
        // $comments = null;
        if($item === 'post'){
            $mainItem = Post::find($itemId);
        } else if($item === 'comment'){
            $mainItem = Comment::find($itemId);
        } else if($item === 'school'){
            $mainItem = School::find($itemId);
        } else if($item === 'class'){
            $mainItem = ClassModel::find($itemId);
        } else if($item === 'lesson'){
            $mainItem = Lesson::find($itemId);
        } else if($item === 'discussion'){
            $mainItem = Discussion::find($itemId);
        } else if($item === 'read'){
            $mainItem = Read::find($itemId);
        } else {
            return response()->json([
                'message' => "unsuccessful. {$item} is not valid for comments."
            ]);
        }

        if($mainItem){
            return CommentResource::collection($mainItem->comments()->latest()->paginate(5));
            
            // return response()->json([
            //     'message' => "successful",
            //     'comments' => $comments,
            // ]);
        } else {
            return response()->json([
                'message' => "unsuccessful. {$item} was not found"
            ]);
        }
    }

    public function commentGet(Request $request,$comment)
    {
        $item = null;
        $item = Comment::find($comment);

        if (!$item) {
            return response()->json([
                'message' => 'unsuccessful. comment does not exist.'
            ]);
        }
        return response()->json([
            'message' => "successful",
            'comment' => new CommentResource($item)
        ]);
    }
/////////////////////////////////////////////////////////////////
    public function postCreate(Request $request)
    {
        $user = auth()->user();
        $id = null;
        $account = null;
        $post = null;
        $type = null;
        // dd($request->content);
        if ($request->has('account')) {
            $id = $request->account_id;
            if ($request->account === 'learner') {
                $account = $user->learner;
            } else if ($request->account === 'parent') {
                $account = $user->parent;
            } else if ($request->account === 'facilitator') {
                $account = $user->facilitator;
            } else if ($request->account === 'professional') {
                $account = Professional::find($id);
                if (!$account || $account->user_id != $user->id) {
                    return response()->json([
                        'message' => "You are not the owner of this {$request->account}"
                    ], 422);
                }
            } else if ($request->account === 'school') {
                $account = School::find($id);
                if (!$account || $account->user_id != $user->id) {
                    return response()->json([
                        'message' => "You are not the owner of this {$request->account}"
                    ], 422);
                }
            }
        } else {
            return response()->json([
                'message' => 'inadequate data. Account required.'
            ], 422);
        }

        DB::beginTransaction();
        try {
            if($account){
                $request->validate([
                    'content' => 'nullable|string',
                ]);
                if ($account->user_id === $user->id) {
                    $post = $account->posts()->create([
                        'content' => $request->content,
                    ]);
                } else {
                    return response()->json([
                        'message' => "The {$request->account} account does'nt belong to you."
                    ], 422);
                }
            } else {
                DB::rollback();
                return response()->json([
                    'message' => "{$request->account} does'nt exist."
                ], 422);
            }

            if (!$post) {
                DB::rollback();
                return response()->json([
                    'message' => 'post creation unsuccessful'
                ], 422);
            } else {
                $request->validate([
                    'file' => 'nullable|file',
                    'fileType' => 'nullable|string',
                ]);

                $file = null;
                if ($request->hasFile('file')) {
                    $fileDetails = $this->getFileDetails($request->file('file'));
                    // $fileArray['mime'] = $file->getClientMimeType();
                    // $fileArray['size'] = $file->getSize();
                    // $fileArray['path'] = uploadYourEduFile($file);
                    // $createdFile = null;
                    // if ($request->fileType === 'image') {
                    //     $createdFile = $account->addedImages()->create($fileArray);
                    //     $post->images()->attach($createdFile);
                    // } else if ($request->fileType === 'video') {
                    //     $createdFile = $account->addedVideos()->create($fileArray);
                    //     $post->videos()->attach($createdFile);
                    // } else if ($request->fileType === 'audio') {
                    //     $createdFile = $account->addedAudio()->create($fileArray);
                    //     $post->audios()->attach($createdFile);
                    // } else if ($request->fileType === 'file') {
                    //     $createdFile = $account->addedAudio()->create($fileArray);
                    //     $post->files()->attach($createdFile);
                    // }
                    
                    $file = $this->accountCreateFile(
                        $fileDetails['mime'],
                        $account, 
                        $fileDetails,
                        $post
                    );
                }

                // dd(['file'=> $file,'filetype'=> $fileArray]);
            }

            $input = [];
            if ($request->has('type')) {

                if ($request->type === 'book') {
                    $request->validate([
                        'title' => 'required|string',
                        'author' => 'nullable|string',
                        'about' => 'nullable|string',
                        'published' => 'nullable|date',
                        'previewFile' => 'nullable|file',
                        'previewFileType' => 'nullable|string',
                    ]);

                    $input['title'] = $request->title;
                    $input['author'] = $request->author;
                    $input['about'] = $request->about;
                    $input['published'] = Carbon::parse($request->published)->toDateTimeString();  
                    $type = $account->booksAdded()->create($input);
                    $type->bookable()->associate($post);
                    $type->save();
                    // dd($type);
                } else if ($request->type === 'riddle') {
                    $request->validate([
                        'riddle' => 'required|string',
                        'author' => 'nullable|string',
                        'published' => 'nullable|date',
                        'previewFile' => 'nullable|file',
                        'previewFileType' => 'nullable|string',
                    ]);

                    $input['author'] = $request->author;
                    $input['riddle'] = $request->riddle;
                    $input['published'] = Carbon::parse($request->published)->toDateTimeString();  
                    // $type = $post->riddles()->create($input); 
                    $type = $account->riddlesAdded()->create($input);
                    $type->riddleable()->associate($post);
                    $type->save();
                } else if ($request->type === 'poem') {
                    $request->validate([
                        'title' => 'required|string',
                        'author' => 'nullable|string',
                        'about' => 'nullable|string',
                        'sections' => 'required|string',
                        'published' => 'nullable|date',
                        'previewFile' => 'nullable|file',
                        'previewFileType' => 'nullable|string',
                    ]);

                    $input['title'] = $request->title;
                    $input['author'] = $request->author;
                    $input['about'] = $request->about;
                    $input['published'] = Carbon::parse($request->published)->toDateTimeString();  
                    // $type = $post->riddles()->create($input); 
                    $type = $account->poemsAdded()->create($input);
                    $type->poemable()->associate($post);
                    $type->save();
                    $sections = json_decode($request->sections);
                    foreach ($sections as $key => $section) {
                        $type->poemSections()->create([
                            'body' => $section
                        ]);
                    }
                } else if ($request->type === 'activity') {
                    $request->validate([
                        'description' => 'nullable|string',
                        'published' => 'nullable|date',
                        'previewFile' => 'required|file',
                        'previewFileType' => 'required|string',
                    ]);

                    $input['description'] = $request->description;
                    $input['published'] = Carbon::parse($request->published)->toDateTimeString();  
                    $type = $account->activitiesAdded()->create($input);
                    $type->activityfor()->associate($post);
                    $type->save();
                } else if ($request->type === 'question') {
                    $request->validate([
                        'question' => 'required|string',
                        'published' => 'nullable|date',
                        'previewFile' => 'nullable|file',
                        'previewFileType' => 'nullable|string',
                    ]);

                    $input['question'] = $request->question;
                    $input['state'] = 'PENDING';
                    $input['published'] = Carbon::parse($request->published)->toDateTimeString();  
                    // $type = $post->questions()->create($input);
                    $type = $account->questionsAdded()->create($input);
                    $type->questionable()->associate($post);
                    $type->save();
                } 

                if ($type) {

                    $file = null;
                    if ($request->hasFile('previewFile')) {
                        $fileDetails = $this->getFileDetails($request->file('previewFile'));

                        $file = $this->accountCreateFile(
                            $fileDetails['mime'],
                            $account, 
                            $fileDetails,
                            $type
                        );
                    }
                    DB::commit();
                    return response()->json([
                        'message' => 'successful',
                        'post' => new PostResource($post),
                    ]);
                } else {
                    DB::rollback();
                    return response()->json([
                        'message' => 'unsuccessful',
                        'post' => $post,
                    ]);
                }
            }

            DB::commit();
            return response()->json([
                'message' => 'successful',
                'post' => new PostResource($post),
            ]);
        } catch (\Throwable $th) {
            if($file){
                Storage::delete($file->path);
            }
            DB::rollback();
            // return response()->json([
            //     'message' => 'unsuccessful',
            //     'post' => $post,
            //     'type' => $type,
            // ]);
            throw $th;
        }

    }

    public function posts()
    {
        $posts = null;
        try {
            $posts = Post::all();
            $profiles = Profile::all();
            // dd($posts->merge($profiles));
            $all = paginate($posts->merge($profiles),5);
            return response()->json([
                'message' => 'successful',
                'posts' => $all
            ]);            
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Unsuccessful. Something unexpected happened. Please try again later.',
            ]);
            // throw $th;
        }
    }

    public function postEdit(Request $request,$post, $account, $accountId)
    {
        $mainPost = Post::find($post);
        
        if ($account === 'learner') {
            $mainAccount = Learner::find($accountId);
        } else if ($account === 'parent') {
            $mainAccount = ParentModel::find($accountId);
        } else if ($account === 'facilitator') {
            $mainAccount = Facilitator::find($accountId);
        } else if ($account === 'professional') {
            $mainAccount = Professional::find($accountId);
        } else if ($account === 'school') {
            $mainAccount = School::find($accountId);
        }

        if($mainAccount->user_id !== auth()->id()){
            return response()->json([
                'message' => 'unsuccessful. you do not own this account'
            ]);
        }

        $request->validate([
            'content' => 'nullable|string'
        ]);

        DB::beginTransaction();
        $mainPost->update([
            'content' => $request->content
        ]);

        $input = [];
        if ($request->has('type')) {

            if ($request->type === 'book') {
                $request->validate([
                    'title' => 'required|string',
                    'author' => 'nullable|string',
                    'about' => 'nullable|string',
                    'published' => 'nullable|date',
                    'previewFile' => 'nullable|file',
                    'previewFileType' => 'nullable|string',
                ]);

                $input['title'] = $request->title;
                $input['author'] = $request->author;
                $input['about'] = $request->about;
                $input['published'] = Carbon::parse($request->published)->toDateTimeString();  
                $type = $mainAccount->booksAdded()->where('id',$request->typeId)->first();
                if ($type) {
                    $type->update($input);
                }
            } else if ($request->type === 'riddle') {
                $request->validate([
                    'riddle' => 'required|string',
                    'author' => 'nullable|string',
                    'published' => 'nullable|date',
                    'previewFile' => 'nullable|file',
                    'previewFileType' => 'nullable|string',
                ]);

                $input['author'] = $request->author;
                $input['riddle'] = $request->riddle;
                $input['published'] = Carbon::parse($request->published)->toDateTimeString();  
                $type = $mainAccount->riddlesAdded()->where('id',$request->typeId)->first();
                if ($type) {
                    $type->update($input);
                }
            } else if ($request->type === 'poem') {
                $request->validate([
                    'title' => 'required|string',
                    'author' => 'nullable|string',
                    'about' => 'nullable|string',
                    'sections' => 'required|string',
                    'published' => 'nullable|date',
                    'previewFile' => 'nullable|file',
                    'previewFileType' => 'nullable|string',
                ]);

                $input['title'] = $request->title;
                $input['author'] = $request->author;
                $input['about'] = $request->about;
                $input['published'] = Carbon::parse($request->published)->toDateTimeString(); 
                $type = $mainAccount->poemsAdded()->where('id',$request->typeId)->type();
                if ($type) {
                    $type->update($input);
                } else {
                    DB::rollback();
                    return response()->json([
                        'message' => 'unsuccessful. poem was not found'
                    ]);
                }
                $sections = json_decode($request->sections);
                foreach ($sections as $key => $section) {
                    $poemSection = $type->poemSections()->where('id',$section->id)->first();
                    if($poemSection){
                        $poemSection->update([
                            'body' => $section->body
                        ]);
                    } else {
                        DB::rollback();
                        return response()->json([
                            'message' => 'unsuccessful. poem section was not found'
                        ]);
                    }
                }
            } else if ($request->type === 'activity') {
                $request->validate([
                    'description' => 'nullable|string',
                    'published' => 'nullable|date',
                    'previewFile' => 'required|file',
                    'previewFileType' => 'required|string',
                ]);

                $input['description'] = $request->description;
                $input['published'] = Carbon::parse($request->published)->toDateTimeString();  
                $type = $mainAccount->activitiesAdded()->where('id',$request->typeId)->furst();
                if ($type) {
                    $type->update($input);
                } else {
                    DB::rollback();
                    return response()->json([
                        'message' => 'unsuccessful. activity was not found'
                    ]);
                }
            } else if ($request->type === 'question') {
                $request->validate([
                    'question' => 'required|string',
                    'published' => 'nullable|date',
                    'previewFile' => 'nullable|file',
                    'previewFileType' => 'nullable|string',
                ]);

                $input['question'] = $request->question;
                $input['state'] = 'PENDING';
                $input['published'] = Carbon::parse($request->published)->toDateTimeString(); 
                $type = $mainAccount->questionsAdded()->where('id',$request->typeId)->first();
                if ($type) {
                    $type->update($input);
                }  else {
                    DB::rollback();
                    return response()->json([
                        'message' => 'unsuccessful. question was not found'
                    ]);
                }
            } 

            if ($type) {

                $file = null;
                if ($request->hasFile('previewFile')) {
                    $fileDetails = $this->getFileDetails($request->file('previewFile'));

                    $file = $this->accountCreateFile(
                        $fileDetails['mime'],
                        $mainAccount, 
                        $fileDetails,
                        $type
                    );
                }
                DB::commit();
                return response()->json([
                    'message' => 'successful',
                    'post' => new PostResource($mainPost),
                ]);
            } else {
                DB::rollback();
                return response()->json([
                    'message' => 'unsuccessful',
                    'post' => $mainPost,
                ]);
            }
        }

        DB::commit();
        return response()->json([
            'message' => 'successful',
            'post' => new PostResource($mainPost),
        ]);
    }

    public function postDelete($post, $account, $accountId)
    {
        $mainPost = Post::find($post);
        
        try {
                
            $mainPost->delete();
            return response()->json([
                'message' => "successful"
            ]);
        } catch (\Throwable $th) {
            throw $th;
            return response()->json([
                'message' => "unsuccessful"
            ]);
        }
    }

    public function postGet(Request $request,$post)
    {
        $item = null;
        $item = Post::find($post);

        if (!$item) {
            return response()->json([
                'message' => 'unsuccessful. post does not exist.'
            ]);
        }
        return new PostResource($item);
    }

    public function postsGet(Request $request, $account, $accountId)
    {
        $mainAccount = null;
        if ($account === 'learner') {
            $mainAccount = Learner::find($accountId);
        } else if ($account === 'parent') {
            $mainAccount = ParentModel::find($accountId);
        } else if ($account === 'facilitator') {
            $mainAccount = Facilitator::find($accountId);
        } else if ($account === 'school') {
            $mainAccount = School::find($accountId);            
        } else if ($account === 'professional') {
            $mainAccount = Professional::find($accountId);
        }

        if (!$mainAccount) {
            return response()->json([
                'message' => 'unsuccessful. account does not exist.'
            ]);
        }

        return PostResource::collection($mainAccount->posts()->latest()->paginate(5));
    }
///////////////////////////////////////////////////////////////////
    public function profileUpdate(Request $request, $data)
    {
        $profile = null;
        $input = [];

        $profile = Profile::find($data);

        try {
            if ($profile) {
                $input['about'] = $request->has('about') && !is_null($request->about) ? 
                    $request->about : null;
                $input['name'] = $request->has('name') && !is_null($request->name) ? 
                    $request->name : null;
                $input['interests'] = $request->has('interests') && !is_null($request->interests) ? 
                    $request->interests : null;
                $input['company'] = $request->has('company') && !is_null($request->company) ? 
                    $request->company : null;
                $input['occupation'] = $request->has('occupation') && !is_null($request->occupation) ? 
                    $request->occupation : null;
                $input['address'] = $request->has('address') && !is_null($request->address) ? 
                    $request->address : null;
                $input['location'] = $request->has('location') && !is_null($request->location) ? 
                    $request->location : null;

                $profile->update($input);

                return response()->json([
                    'message' => "successful",
                    'profile' => $profile
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'message' => "unsuccessful"
            ],422);
            // throw $th;
        }
    }

    public function profileGet($account, $accountId)
    {
        // return [$account, $accountId];
        $mainAccount = null;

        try {
            if ($account === 'learner') {
                $mainAccount = Learner::find($accountId);
            } else if ($account === 'parent') {
                $mainAccount = ParentModel::find($accountId);
            } else if ($account === 'facilitator') {
                $mainAccount = Facilitator::find($accountId);
            } else if ($account === 'school') {
                $mainAccount = School::find($accountId);            
            } else if ($account === 'professional') {
                $mainAccount = Professional::find($accountId);
            }
    
            if ($mainAccount && $mainAccount->profile) {

                return response()->json([
                    'status' => true,
                    'account' => $account,
                    'profile' => new ProfileResource(Profile::find(
                        $mainAccount->profile->id
                    )),
                ]);
            }
    
            return response()->json([
                'status' => false,
                'message' => "profile doesn't exist.",
            ], 422);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => "something unexpected happened",
            ], 422);
            throw $th;
        }
    }

    public function index ()
    {
        return view('youredu');
    }

    private function ageInappropriateMessage($who = 'user',$school = false)
    {
        if ($school) {
            return 'You must be at least 18, to be able to own a school on this platform.';
        } else {
            return "You must be at least 18 years to be a {$who}. If you meet this requirement, then update your date of birth on the welcome or profile page";
        }
    }

    public function userCreate($request, $parent = false)
    {
        $input = [];
        $input['first_name'] = $request->new_first_name;
        $input['last_name'] = $request->new_last_name;
        $input['other_names'] = $request->new_other_names;
        $input['username'] = $request->new_username;
        $input['email'] = $request->new_email;
        $input['dob'] = Carbon::parse($request->new_dob);
        $input['password'] = $request->new_password;
        $input['password_confirmation'] = $request->new_password_confirmation;

        $validator = Validator::make($input,[
            'first_name' => 'string',
            'last_name' => 'string',
            'other_names' => 'nullable|string',
            'username' => 'required|string|unique:users,username|min:8',
            'email' => 'nullable|email|unique:users,email',
            'dob' => 'required|date',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required',
        ]);

        if ($validator->fails()) {
            return [
                'status' => 'learner',
                'errors' => $validator->errors(),
            ];
        }

        $userNew = User::create($input);

        if($parent){
        
            $input = [];
            $input['first_name'] = $request->parent_first_name;
            $input['last_name'] = $request->parent_last_name;
            $input['other_names'] = $request->parent_other_names;
            $input['username'] = $request->parent_username;
            $input['email'] = $request->parent_email;
            $input['dob'] = $request->parent_dob;
            $input['password'] = $request->parent_password;
            $input['password_confirmation'] = $request->parent_password_confirmation;

            $validator = Validator::make($input,[
                'first_name' => 'string',
                'last_name' => 'string',
                'other_names' => 'nullable|string',
                'username' => 'string|unique:users,username|min:8',
                'email' => 'nullable|email|unique:users,email',
                'dob' => 'required|date',
                'password' => 'required|string|min:8|confirmed',
                'password_confirmation' => 'required|string',
            ]);

            if ($validator->fails()) {
                return [
                    'status' => 'parent',
                    'errors' => $validator->errors(),
                ];
            }

            $userNewParent = User::create($input);

            return [
                'learner' => $userNew, 
                'parent' => $userNewParent, 
                'errors' => null,
            ];
        } else{
            return [
                'new' => $userNew, 
                'errors' => null
            ];
        }
        
    }

    public function create(Request $request)
    {

        $request->validate([
            'create' => 'string',
            'creator' => 'string',
            'user_id' => 'integer',
            'school_id' => 'nullable|integer',
            'other_id' => 'nullable|integer',
            'other_username' => 'nullable|string',
            'parent_role' => 'nullable|string',
            'name' => 'nullable|string',
            'email' => 'nullable|email',
            'school_type' => 'nullable|string',
            'company_name' => 'string',
            'description' => 'nullable|string',
            'relationship' => 'nullable|string',
            'relationship_description' => 'nullable|string',
        ]);

        if ($request->create === 'learner') {
            if ($request->creator === 'parent') {

                $parent = null;
                $learner = null;

                if ($request->user_id) {
                    $learner = Learner::find($request->user_id);
                } else if ($request->username) {
                    $learner = User::where('username',$request->username)->first()->learner;
                }
                
                if ($request->other_id) {
                    $parent = ParentModel::find($request->other_id);
                } else if ($request->other_username) {
                    $parent = User::where('username',$request->other_username)->first()->parent;
                } 
                
                try {
                    
                    DB::beginTransaction();

                    if ($request->other_user_id && !$parent) {
                        $userParent = User::find($request->other_user_id);
                        
                        if (!$userParent->dob || now()->diffInYears($userParent->dob) < 18 || $userParent->dob->year === $userParent->created_at->year) {
                            return response()->json([
                                'status' => false,
                                'message' => $this->ageInappropriateMessage('parent')
                            ]);
                        }

                        $parent = $userParent->parent()->create([
                            'name' => $userParent->full_name
                        ]);
                    }
    
                    if (! $learner) {
                        
                        $userLearnerArray = $this->userCreate($request);

                        if ($userLearnerArray['errors']) {
                            return response()->json([
                                'status' => $userLearnerArray['status'],
                                'errors' => $userLearnerArray['errors']
                            ]);
                        }

                        $userLearner = $userLearnerArray['new'];

                        if ($userLearner->learner()->exists()) {
                            return response()->json([
                                'status' => false,
                                'message' => 'You are already a learner. You can do may things with the same learner account'
                            ]);
                        }
    
                        $learner = $userLearner->learner()->create([
                            'name' => $userLearner->full_name
                        ]);
                    }
    
                    if ($learner && $parent) {

                        if ($request->parent_role && ($request->parent_role === 'FATHER' || $request->parent_role === 'MOTHER')) {
                            $parentRole = $request->parent_role;
                        } else {
                            $parentRole = 'GUARDIAN';
                        }
    
                        $parent->learners()->attach($learner->id,[
                            'role'=> $parentRole
                        ]);
    
                        DB::commit();
                        return response()->json([
                            'status'=> (bool) $learner,
                            'learner'=> $learner,
                        ]);
                    }
                    DB::rollback();
                    return response()->json([
                        'status'=> (bool) $learner,
                        'learner'=> $learner,
                        'parent'=> $parent,
                        'message'=> 'unsuccessful',
                    ],401);
                } catch (\Throwable $th) {
                    DB::rollback();
                    throw $th;
                }
                
            } else if ($request->creator === 'school') {

                $learner = null;
                $parent = null;
                
                if ($request->other_id) {
                    $parent = ParentModel::find($request->other_id);
                } else if ($request->other_username) {
                    $parent = User::where('username',$request->other_username)->first()->parent;
                } 
                
                try {
                    DB::beginTransaction();

                    if ($parent) {
                        $userLearner = $this->userCreate($request);
                    } else {
                        $userLearnerParentArray = $this->userCreate($request,true);

                        if ($userLearnerParentArray['errors']) {
                            return response()->json([
                                'status' => $userLearnerParentArray['status'],
                                'errors' => $userLearnerParentArray['errors']
                            ]);
                        }
                        
                        $userLearner = $userLearnerParentArray['learner'];
                        $userParent = $userLearnerParentArray['parent'];
                        // list($userLearner, $userParent) = $userLearnerParentArray;
                            
                        if (!$userParent->dob || now()->diffInYears($userParent->dob) < 18 || $userParent->dob->year === $userParent->created_at->year) {
                            return response()->json([
                                'status' => false,
                                'message' => $this->ageInappropriateMessage('parent')
                            ]);
                        }

                        if ($userParent->parent()->exists()) {
                            return response()->json([
                                'status' => false,
                                'message' => 'You are already a parent. You can add his/her parent account to your school'
                            ]);
                        }

                        $parent = $userParent->parent()->create([
                            'name' => $userParent->full_name,
                        ]);

                        if ($userLearner->learner()->exists()) {
                            return response()->json([
                                'status' => false,
                                'message' => 'User is already a learner. You can add his/her learner account to your school'
                            ]);
                        }

                        $learner = $userLearner->learner()->create([
                            'name' => $userLearner->full_name,
                        ]);

                    }

                    if ($parent && $learner) {

                        if ($request->parent_role && ($request->parent_role === 'FATHER' || $request->parent_role === 'MOTHER')) {
                            $parentRole = $request->parent_role;
                        } else {
                            $parentRole = 'GUARDIAN';
                        }

                        $parent->learners()->attach($learner->id,[
                            'role'=> $parentRole,
                        ]);

                        $school = School::find($request->school_id);
                        if ($school) {

                            if ($school->role === 'VIRTUAL' || !$request->type || $request->type != 'TRADITIONAL' || $request->type != 'OTHER') {
                                $type = 'VIRTUAL';
                            } else {
                                $type = $request->type;
                            }
                            $school->learners()->attach($learner,['type'=> $type]);

                            DB::commit();
                            return response()->json([
                                'status'=> true,
                                'learner'=> $learner,
                                'parent'=> $parent,
                            ]);
                        } 
                    }
                    
                    DB::rollback();
                    return response()->json([
                        'status'=> false,
                        'learner'=> $learner,
                        'parent'=> $parent,
                        'message'=> 'unsuccessful',
                    ],401);
                } catch (\Throwable $th) {
                    DB::rollback();
                    throw $th;
                }
                
            } else if ($request->creator === 'user') {
                //tested
                
                $user = User::find($request->user_id);
                $parent = null;

                if ($request->other_id) {
                    $parent = ParentModel::find($request->other_id);
                } else if ($request->other_username) {
                    $parent =User::where('username',$request->other_username)->first()->parent;
                } else {
                    $parent = $user->parent;
                }
                
                try {
                    DB::beginTransaction();

                    if ((!$user->dob || now()->diffInYears($user->dob, true) < 18 || $user->dob->year === $user->created_at->year) && !$parent) {
                        
                        $userParentArray = $this->userCreate($request);
                        if ($userParentArray['errors']) {
                            return response()->json([
                                'status' => $userParentArray['status'],
                                'errors' => $userParentArray['errors']
                            ]);
                        }
                        $userParent = $userParentArray['new'];

                        if (!$userParent->dob || now()->diffInYears($userParent->dob, true) < 18 || $userParent->dob->year === $userParent->created_at->year) {
                            $parent = $userParent->parent()->create([
                                'name' => $userParent->full_name
                            ]);
                        }

                        if (!$parent) {
                            DB::rollback();
                            return response()->json([
                                'status' => false,
                                'message' => 'You require a parent. If you are above 18, please do indicate it by editing your user details in the welcome page.'
                            ]);
                        }
                    }

                    if ($request->name ==='') {
                        $inputName =  $user->full_name;
                    } else {
                        $inputName = $request->name;
                    }

                    if ($user) {

                        if ($user->has('parent')) {
                            DB::rollback();
                            return response()->json([
                                'status' => false,
                                'user' => "You are a parent. You can't be a learner too" //need to make a decision on whether you can be a parent and a learner
                            ]);
                        }
                        $learner = $user->learner()->create([
                            'name' => $inputName
                        ]);

                        if ($parent) {
                            $parent->learners()->attach($learner->id,[
                                'role' => $request->parent_role
                            ]);
                        }
                    } else{
                        DB::rollback();
                        return response()->json([
                            'status' => (bool) $user,
                            'user' => 'user not found'
                        ]);
                    }

                    DB::commit();
                    return response()->json([
                        'status' => (bool) $learner,
                        'learner' => $learner
                    ]);
                } catch (\Throwable $th) {
                    DB::rollback();
                    throw $th;
                }
            }
        } else if ($request->create === 'facilitator') {
            if ($request->creator === 'school') {
                
                $facilitator = null;
                $school = null;
                $userFacilitator = null;

                if ($request->other_id) {
                    $facilitator = Facilitator::find($request->other_id);
                }

                try {
                    if (! $facilitator) {

                        DB::beginTransaction();

                        $userFacilitatorArray = $this->userCreate($request);

                        if ($userFacilitatorArray['errors']) {
                            return response()->json([
                                'status' => $userFacilitatorArray['status'],
                                'errors' => $userFacilitatorArray['errors']
                            ]);
                        }

                        $userFacilitator = $userFacilitatorArray['new'];

                        if (now()->diffInYears($userFacilitator->dob) < 18 ||  $userFacilitator->dob->year === $userFacilitator->created_at->year) {
                            
                            DB::rollback();
                            return response()->json([
                                'status' => false,
                                'facilitator' => $this->ageInappropriateMessage('facilitator')
                            ]);
                        }

                        if ($userFacilitator->facilitator()->exists()) {
                            return response()->json([
                                'status' => false,
                                'message' => 'User is already a facilitator. You can just add his/her account to your school'
                            ]);
                        }

                        $facilitator = $userFacilitator->facilitator()->create([
                            'name' => $userFacilitator->full_name
                        ]);
                    } 
                    
                    if ($facilitator) {

                        $school = School::find($request->school_id);
                        
                        if ($school) {

                            if (!$request->relationship || $school->role === 'VIRTUAL') {
                                $relationship = 'VIRTUAL';
                            } else {
                                $relationship = $request->relationship;
                            }

                            $school->facilitators()->save($facilitator,[
                                'relationship' => $relationship,
                                'relationship_description' => $request->relationship_description,
                            ]);

                            DB::commit();
                            return response()->json([
                                'status' => (bool) $facilitator,
                                'facilitator' => $facilitator
                            ]);
                        }
                    }

                    DB::rollback();
                    return response()->json([
                        'status' => false,
                        'facilitator' => $facilitator,
                        'school' => $school,
                        'message' => 'unsuccessful',
                    ], 401);
                } catch (\Throwable $th) {
                    DB::rollback();
                    throw $th;
                }
            
            } else if ($request->creator === 'user') {
                //tested
                $user = User::find($request->user_id);
                $facilitator = null;
                try {
                    DB::beginTransaction();
                    if ($user) {

                        if (!$user->dob || now()->diffInYears($user->dob) < 18  ||  $user->dob->year === $user->created_at->year) {
                            return response()->json([
                                'status' => (bool) $user,
                                'message' => $this->ageInappropriateMessage('facilitator')
                            ]);
                        }

                        if ($user->facilitator()->exists()) {
                            return response()->json([
                                'status' => false,
                                'message' => 'You are already a facilitator. You can do may things with the same facilitator account'
                            ]);
                        }
                        
                        if ($request->name ==='') {
                            $inputName =  $user->full_name;
                        }else{
                            $inputName =  $request->name;
                        }
    
                        $facilitator = $user->facilitator()->create(['name' => $inputName]);
                    } else{
                        return response()->json([
                            'status' => (bool) $user,
                            'message' => 'user not found'
                        ]);
                    }
    
                    DB::commit();
                    return response()->json([
                        'status' => (bool) $facilitator,
                        'facilitator' => $facilitator
                    ]);
                } catch (\Throwable $th) {
                    DB::rollback();
                    throw $th;
                }
            
            }
        } else if ($request->create === 'professional') {
            if ($request->creator==='user') {
                //tested 
                $user = User::find($request->user_id);

                try {
                    if ($user) {

                        if (!$user->dob || now()->diffInYears($user->dob) < 18 ||  $user->dob->year === $user->created_at->year) {
                            return response()->json([
                                'status' => (bool) $user,
                                'message' => $this->ageInappropriateMessage('professional')
                            ]);
                        }

                        DB::beginTransaction();
                        if ($request->name ==='') {
                            $inputName =  $user->full_name;
                        }else{
                            $inputName =  $request->name;
                        }
    
                        $professional = $user->professionals()->create([
                            'name' => $inputName, 
                            'description' => $request->description]);
                    } else{
                        return response()->json([
                            'status' => (bool) $user,
                            'user' => 'user not found'
                        ]);
                    }
    
                    DB::commit();
                    return response()->json([
                        'status' => (bool) $professional,
                        'professional' => $professional
                    ]);
                } catch (\Throwable $th) {
                    DB::rollback();
                    throw $th;
                }
            } else if ($request->creator === 'school') {
                // tested
                $professional = null;
                $school = null;

                if ($request->other_id) {
                    $professional = Professional::find($request->other_id);
                }

                try {
                    DB::beginTransaction();

                    if (! $professional) {
                        $userProfessionalArray = $this->userCreate($request);

                        if ($userProfessionalArray['errors']) {
                            return response()->json([
                                'status' => $userProfessionalArray['status'],
                                'errors' => $userProfessionalArray['errors']
                            ]);
                        }

                        $userProfessional = $userProfessionalArray['new'];
    
                        if (now()->diffInYears($userProfessional->dob) < 18  ||  $userProfessional->dob->year === $userProfessional->created_at->year) {
                            DB::rollback();
                            return response()->json([
                                'status' => false,
                                'professional' => $this->ageInappropriateMessage('professional')
                            ]);
                        }
                        $professional = $userProfessional->professionals()->create([
                            'name' => $userProfessional->full_name
                        ]);
                    }
                    
                    if ($professional) {
    
                        $school = School::find($request->school_id);
                        
                        if ($school) {

                            if (!$request->relationship || $school->role === 'VIRTUAL') {
                                $relationship = 'VIRTUAL';
                            } else {
                                $relationship = $request->relationship;
                            }

                            $school->professionals()->save($professional,[
                                'relationship' => $relationship,
                                'relationship_description' => $request->relationship_description,
                            ]);
    
                            DB::commit();
                            return response()->json([
                                'status' => (bool) $professional,
                                'professional' => $professional
                            ]);
                        }
                    }
    
                    DB::rollback();
                    return response()->json([
                        'status' => false,
                        'professional' => $professional,
                        'school' => $school,
                        'message' => 'unsuccessful',
                    ], 401);
                } catch (\Throwable $th) {
                    DB::rollback();
                    throw $th;
                }
            }
        } else if ($request->create === 'school') {
            //tested
            $user = User::find($request->user_id);

            try {
                if ($user) {
                    DB::beginTransaction();

                    if (now()->diffInYears($user->dob) < 18  ||  $user->dob->year === $user->created_at->year) {
                        DB::rollback();
                        return response()->json([
                            'status' => false,
                            'message' => $this->ageInappropriateMessage('',true)
                        ]);
                    }

                    if ($request->has('role') && $request->role === 'TRADITIONAL') {
                        $input['role'] = $request->role;
                    }else{
                        $input['role'] = 'VIRTUAL';
                    }
    
                    $school = $user->schools()->create([
                        'company_name' => $request->company_name,
                        'role' => $input['role'],
                    ]);
    
                    if ($school) {
                        $user->admins()->create([
                            'name' => $user->full_name,
                            'role' => 'SCHOOLADMIN',
                            'level' => 10,
                        ]);
                        
                        DB::commit();
                        return response()->json([
                            'status' => (bool) $school,
                            'school' => $school,
                        ]);
                    }
                }
    
                DB::rollback();
                return response()->json([
                    'status' => false,
                    'message' => 'unsuccessful',
                ]);
            } catch (\Throwable $th) {
                DB::rollback();
                throw $th;
            }
            
        } else if ($request->create === 'parent') {
            //tested
            $user = User::find($request->user_id);
            $parent = null;

            try {
                if ($user) {
                    DB::beginTransaction();
    
                    if (now()->diffInYears($user->dob) < 18  ||  $user->dob->year === $user->created_at->year) {
                        DB::rollback();
                        return response()->json([
                            'status' => false,
                            'message' => $this->ageInappropriateMessage('parent')
                        ]);
                    }

                    if ($user->parent()->exists()) {
                        return response()->json([
                            'status' => false,
                            'message' => 'You are already a parent. You can choose to parent many learners with the same parent account'
                        ]);
                    }

                    if ($request->name ==='') {
                        $inputName =  $user->full_name;
                    }else {
                        $inputName = $request->name;
                    }            
    
                    $parent = $user->parent()->create([
                        'name' => $inputName, 
                        'description' => $inputName
                    ]);
                } else{
                    return response()->json([
                        'status' => (bool) $user,
                        'message' => 'unsuccessful'
                    ]);
                }
    
                DB::commit();
                return response()->json([
                    'status' => (bool) $parent,
                    'parent' => $parent
                ]);
            } catch (\Throwable $th) {
                DB::rollback();
                throw $th;
            }
            
        } else if ($request->create === 'group') {
            if ($request->creator === 'learner') {
                
            } else if ($request->creator === 'professional') {
                
            } else if ($request->creator === 'facilitator') {
                
            } else if ($request->creator === 'parent') {
                
            } else if ($request->creator === 'school') {
                
            }
            
        }
    }
}
