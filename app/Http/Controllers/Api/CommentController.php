<?php

namespace App\Http\Controllers\Api;

use App\Events\DeleteComment;
use App\Events\NewComment;
use App\Events\UpdateComment;
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
use \Debugbar;
use Illuminate\Database\Eloquent\Builder;

class CommentController extends Controller
{
    //

    public function commentCreate(Request $request, $item, $itemId)
    {  
        $request->validate([
            'body' => 'nullable|string',
            'accountId' => 'required|string',
            'accountId' => 'required|string',
            'file' => 'nullable|file',
        ]);
        
        if(is_null($request->body) && is_null($request->file)){
            return response()->json([
                'message' => "unsuccessful. there is nothing to add as comment."
            ],422);
        }

        $commentable_owner = null;
        $account = getAccountObject($request->account,$request->accountId);

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
            $comment = null;
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
    
            $dontRollBack = true;
            if ($item === 'post') {
                $post = Post::find($itemId);
    
                $dontRollBack = $this->commentAssociate($comment, $post);
                $commentable_owner = $post->postedby;
            } else if ($item === 'activity') {
                $activity = Activity::find($itemId);

                $dontRollBack = $this->commentAssociate($comment, $activity);
                $commentable_owner = $activity->post->postedby;
            } else if ($item === 'book') {
                $book = Book::find($itemId);

                $dontRollBack = $this->commentAssociate($comment, $book);
                $commentable_owner = $book->post->postedby;
            } else if ($item === 'riddle') {
                $riddle = Riddle::find($itemId);

                $dontRollBack = $this->commentAssociate($comment, $riddle);
                $commentable_owner = $riddle->post->postedby;
            } else if ($item === 'question') {
                $question = Question::find($itemId);

                $dontRollBack = $this->commentAssociate($comment, $question);
                $commentable_owner = $question->post->postedby;
            } else if ($item === 'poem') {
                $poem = Poem::find($itemId);

                $dontRollBack = $this->commentAssociate($comment, $poem);
                $commentable_owner = $poem->post->postedby;
            } else if ($item === 'comment') {
                $oldComment = Comment::find($itemId);

                $dontRollBack = $this->commentAssociate($comment, $oldComment);
            } else if ($item === 'lesson') {
                $lesson = Lesson::find($itemId);

                $dontRollBack = $this->commentAssociate($comment, $lesson);
            } else if ($item === 'class') {
                $class = ClassModel::find($itemId);

                $dontRollBack = $this->commentAssociate($comment, $class);
            } else if ($item === 'request') {
                $eduRequest = YourEduRequest::find($itemId);

                $dontRollBack = $this->commentAssociate($comment, $eduRequest);
            } else if ($item === 'admission') {
                $admission = Admission::find($itemId);

                $dontRollBack = $this->commentAssociate($comment, $admission);
            } else if ($item === 'ban') {
                $ban = Ban::find($itemId);

                $dontRollBack = $this->commentAssociate($comment, $ban);
            } else if ($item === 'flag') {
                $flag = Flag::find($itemId);

                $dontRollBack = $this->commentAssociate($comment, $flag);
            } else if ($item === 'answer') {
                $answer = Answer::find($itemId);
    
                $dontRollBack = $this->commentAssociate($comment, $answer);
            } else if ($item === 'keyword') {
    
            } else if ($item === 'word') {
    
            } else if ($item === 'expression') {
                
            } else if ($item === 'character') {
                $character = Character::find($itemId);

                $dontRollBack = $this->commentAssociate($comment, $character);
            } else if ($item === 'school') {
                $school = School::find($itemId);

                $dontRollBack = $this->commentAssociate($comment, $school);
            }
            if (!$dontRollBack) {
                if($file){
                    Storage::delete($file->path);
                }
                DB::rollback();
                return response()->json([
                    'message' => "unsuccessful. {$item} does not exist or comment was not created"
                ],422);
            }
            
            DB::commit();
            $comment = Comment::with('commentable')->find($comment->id);
            $commentResource = new CommentResource($comment);
            broadcast(new NewComment([
                'comment' => $commentResource,
                'item' => $item,
                'itemId' => $itemId,
                'commentable_owner' => $commentable_owner
            ]))->toOthers();
            Debugbar::info($commentable_owner);
            return response()->json([
                'message' => "successful",
                'comment' => $commentResource,
            ]);
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

    private function commentAssociate($comment, $item)
    {
        if ($comment && $item) {
            $comment->commentable()->associate($item);
            $comment->save();
        } else {
            return false;
        }
        return true;
    }

    public function commentEdit(Request $request, $comment)
    {
        $request->validate([
            'body' => 'required|string',
            // 'file' => 'nullable|file',
        ]);
        
        
        // for now, body must be required, on a later date, it wont be. but we will 
        //check to ensure that the update doesnt lead to an empty comment (without body and file)

        $mainAccount = getAccountObject($request->account,$request->accountId);

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
                } else {
                    DB::rollback();
                    return response()->json([
                        'message' => "unsuccessful. there is no such comment for this accout user."
                    ]);
                }

                DB::commit();
                $mainComment = Comment::with('commentable')->find($mainComment->id);
                $commentResource = new CommentResource($mainComment);
                broadcast(new UpdateComment([
                    'comment' => $commentResource,
                    'mainComment' => $mainComment,
                ]))->toOthers();
                return response()->json([
                    'message' => "successful",
                    'comment' => $commentResource,
                ]);
            
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
        $mainComment = Comment::with(['commentable'])->find($comment);
        $item = getAccountString($mainComment->commentable_type);
        $itemId = $mainComment->commentable_id;
        $account = null;
        $accountId = null;
        if ($item === 'post') {
            $account = getAccountString(get_class($mainComment->commentable->postedby));
            $accountId = $mainComment->commentable->postedby->id;
        } else if ($item === 'book' || $item === 'poem' || $item === 'activity') {
            $account = getAccountString(get_class($mainComment->commentable->post->postedby));
            $accountId = $mainComment->commentable->post->postedby->id;
        } 
        try {
            $mainComment->delete();
            broadcast(new DeleteComment([
                'commentId' => $comment,
                'item' => $item,
                'itemId' => $itemId,
                'account' => $account,
                'accountId' => $accountId,
            ]))->toOthers();
            Debugbar::info([
                'commentId' => $comment,
                'item' => $item,
                'itemId' => $itemId,
                'account' => $account,
                'accountId' => $accountId,
            ]);
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

    public function commentsGet($item, $itemId)
    {
        //items that are commentable
        $mainItem = getAccountObject($item,$itemId);

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
