<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\YourEdu\Activity;
use App\YourEdu\Admin;
use App\YourEdu\Admission;
use App\YourEdu\Answer;
use App\YourEdu\Ban;
use App\YourEdu\Book;
use App\YourEdu\Character;
use App\YourEdu\ClassModel;
use App\YourEdu\Comment;
use App\YourEdu\Discussion;
use App\YourEdu\Facilitator;
use App\YourEdu\Flag;
use App\YourEdu\Learner;
use App\YourEdu\Lesson;
use App\YourEdu\ParentModel;
use App\YourEdu\Poem;
use App\YourEdu\Post;
use App\YourEdu\Professional;
use App\YourEdu\Question;
use App\YourEdu\Read;
use App\YourEdu\Request as YourEduRequest;
use App\YourEdu\Riddle;
use App\YourEdu\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CommentController extends Controller
{
    //

    public function commentCreate(Request $request, $item, $itemId)
    {  
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
        } else if ($request->account === 'school') {
            $account = School::find($accountId);
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
            if ($account->user_id === auth()->id()) {
                DB::beginTransaction();
                $comment = $account->comments()->create([
                    'body' => $request->body
                ]);
                
                $file = null;
                if ($request->hasFile('file')) {
                    $fileDetails = [];
                    $fileDetails = getFileDetails($request->file('file'));

                    $file = accountCreateFile(
                        $fileDetails['mime'],
                        $account, 
                        $fileDetails,
                        $comment
                    );
                }
            } else {
                return response()->json([
                    'message' => "unsuccessful. {$request->account} does not exist or does not belong to you."
                ],422);
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
                    ],422);
                }
            } else if ($item === 'activity') {
                $activity = Activity::find($itemId);

                if ($comment && $activity) {
                    $comment->commentable()->associate($activity);
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
            } else if ($item === 'book') {
                $book = Book::find($itemId);

                if ($comment && $book) {
                    $comment->commentable()->associate($book);
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
            } else if ($item === 'riddle') {
                $riddle = Riddle::find($itemId);

                if ($comment && $riddle) {
                    $comment->commentable()->associate($riddle);
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
            } else if ($item === 'question') {
                $question = Question::find($itemId);

                if ($comment && $question) {
                    $comment->commentable()->associate($question);
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
            } else if ($item === 'poem') {
                $poem = Poem::find($itemId);

                if ($comment && $poem) {
                    $comment->commentable()->associate($poem);
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
            } else if ($item === 'answer') {
                $answer = Answer::find($itemId);
    
                if ($comment && $answer) {
                    $comment->commentable()->associate($answer);
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
                    ],422);
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
        $request->validate([
            'body' => 'required|string',
            // 'file' => 'nullable|file',
        ]);
        
        
        // for now, body must be required, on a later date, it wont be. but we will 
        //check to ensure that the update doesnt lead to an empty comment (without body and file)

        $mainComment = null;
        $mainAccount = null;
        // $file = null;
        if ($request->account === 'learner') {
            $mainAccount = Learner::find($request->accountId);
        } else if ($request->account === 'parent') {
            $mainAccount = ParentModel::find($request->accountId);
        } else if ($request->account === 'facilitator') {
            $mainAccount = Facilitator::find($request->accountId);
        } else if ($request->account === 'professional') {
            $mainAccount = Professional::find($request->accountId);
        } else if ($request->account === 'admin') {
            $mainAccount = Admin::find($request->accountId);
        }

        if (!$mainAccount) {
            return response()->json([
                'message' => "unsuccessful. there is no such {$request->account}."
            ]);
        }

        if ($mainAccount->user_id !== auth()->id()) {
            return response()->json([
                'message' => "unsuccessful. you do not own this account."
            ]);
        }

        try {
                DB::beginTransaction();
                $mainComment = $mainAccount->comments()->where('id', $comment)->first();
                
                if ($mainComment) {
                    $mainComment->update([
                        'body' => $request->body
                    ]);

                    DB::commit();
                    return response()->json([
                        'message' => "successful",
                        'comment' => new CommentResource($mainComment),
                    ]);
                } else {
                    DB::rollback();
                    return response()->json([
                        'message' => "unsuccessful. there is no such comment for this accout user."
                    ]);
                }

                // if ($request->hasFile('file')) {
                //     $fileDetails = [];
                //     $fileDetails = $this->getFileDetails($request->file('file'));

                //     $file = $this->accountCreateFile(
                //         $fileDetails['mime'],
                //         $mainAccount, 
                //         $fileDetails,
                //         $comment
                //     );
                // }
            
        } catch (\Throwable $th) {
            // if($file){
            //     Storage::delete($file->path);
            // }
            DB::rollback();
            // return response()->json([
            //     'message' => "Unsuccessful. Something might have gone wrong. Please try again later."
            // ]);
            throw $th;
        }
    }

    public function commentDelete($comment)
    {
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
        //items that are commentable
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
        } else if($item === 'answer'){
            $mainItem = Answer::find($itemId);
        } else if($item === 'poem'){
            $mainItem = Poem::find($itemId);
        } else if($item === 'activity'){
            $mainItem = Activity::find($itemId);
        } else if($item === 'book'){
            $mainItem = Book::find($itemId);
        } else {
            return response()->json([
                'message' => "unsuccessful, {$item} is not valid for comments."
            ],422);
        }

        if($mainItem){
            return CommentResource::collection($mainItem->comments()->latest()->paginate(5));
        } else {
            return response()->json([
                'message' => "unsuccessful, {$item} was not found."
            ],422);
        }
    }

    public function commentGet($comment)
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
}
