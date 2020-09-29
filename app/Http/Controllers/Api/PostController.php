<?php

namespace App\Http\Controllers\Api;

use App\Events\DeletePost;
use App\Events\NewPost;
use App\Events\UpdatePost;
use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Services\Attachment;
use App\User;
use App\YourEdu\Facilitator;
use App\YourEdu\Follow;
use App\YourEdu\Learner;
use App\YourEdu\ParentModel;
use App\YourEdu\Post;
use App\YourEdu\Professional;
use App\YourEdu\School;
use Carbon\Carbon;
use \Debugbar;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    //

    public function postCreate(Request $request)
    {
        $user = auth()->user();
        $post = null;
        $type = null;
        $file = null;
        $account = getAccountObject($request->account, $request->account_id);

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

                if ($request->hasFile('file')) {
                    $fileDetails = getFileDetails($request->file('file'));
                    
                    $file = accountCreateFile(
                        $account, 
                        $fileDetails,
                        $post
                    );
                }
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
                    
                } else if ($request->type === 'riddle') {
                    $request->validate([
                        'riddle' => 'required|string',
                        'score' => 'nullable',
                        'author' => 'nullable|string',
                        'published' => 'nullable|date',
                        'previewFile' => 'nullable|file',
                        'previewFileType' => 'nullable|string',
                    ]);

                    $input['author'] = $request->author;
                    $input['riddle'] = $request->riddle;

                    if ((int)$request->score > 100) {
                        $input['score_over'] = 100;
                    } else if (is_null($request->score) || (int)$request->score < 5) {
                        $input['score_over'] = 5;
                    } else {
                        $input['score_over'] = (int) $request->score;
                    }

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
                        'score' => 'nullable',
                        'possibleAnswers' => 'nullable|string',
                        'published' => 'nullable|date',
                        'previewFile' => 'nullable|file',
                        'previewFileType' => 'nullable|string',
                    ]);

                    $input['question'] = $request->question;
                    $input['state'] = 'PENDING';

                    if ((int)$request->score > 100) {
                        $input['score_over'] = 100;
                    } else if (is_null($request->score) || (int)$request->score < 5) {
                        $input['score_over'] = 5;
                    } else {
                        $input['score_over'] = (int) $request->score;
                    }

                    $input['published'] = Carbon::parse($request->published)->toDateTimeString();
                    $type = $account->questionsAdded()->create($input);
                    $type->questionable()->associate($post);
                    $type->save();

                    if ($request->has('possibleAnswers') && 
                        !is_null($request->possibleAnswers)) {
                        $possibleAnswers = json_decode($request->possibleAnswers);
                        foreach ($possibleAnswers as $key => $possibleAnswer) {
                            $type->possibleAnswers()->create([
                                'option' => $possibleAnswer,
                            ]);
                        }
                    }
                }  else if ($request->type === 'lesson') {
                    $request->validate([
                        'title' => 'required|string',
                        'description' => 'nullable|string',
                        'ageGroup' => 'nullable|string',
                        'previewFile.*' => 'nullable|file',
                    ]);

                    $input['description'] = $request->description;
                    $input['title'] = $request->title;
                    $input['ageGroup'] = $request->ageGroup;  
                    $type = $account->lessonsAdded()->create($input);
                    $type->ownedby()->associate($account);
                    $type->lessonable()->associate($post);
                    $type->save();
                }

                if ($type) {

                    if ($request->hasFile('previewFile')) {

                        foreach ($request->file('previewFile') as $previewFile) {
                            
                            $fileDetails = getFileDetails($previewFile);
    
                            $file = accountCreateFile(
                                $account, 
                                $fileDetails,
                                $type
                            );
                        }
                    }
                } else {
                    DB::rollback();
                    return response()->json([
                        'message' => 'unsuccessful',
                        'post' => $post,
                    ]);
                }
            }
            
            if ($request->has('attachments')) {
                foreach (json_decode($request->attachments) as $attachment) {
                    Attachment::attach($account,$post,$attachment->attachable,$attachment->attachableId);
                }
            }

            DB::commit();
            $post = Post::with(['questions.images','questions.videos',
            'questions.audios','questions.files','activities.images','activities.videos',
            'activities.files','activities.audios','riddles.images','riddles.videos',
            'riddles.files','riddles.audios','poems.images','poems.videos',
            'poems.files','poems.audios','books.images','books.videos','books.files',
            'books.audios','postedby.profile'])->find($post->id);
            Debugbar::info($post);
            $postResource = new PostResource($post);
            broadcast(new NewPost($postResource))->toOthers();
            return response()->json([
                'message' => 'successful',
                'post' => $postResource,
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

    public function getUserPosts(Request $request)
    {
        $posts = null;
        $parentsLearnerUserIds = [];
        $learner = User::find(auth()->id())->learner;
        try {
            if ($learner && $learner->parents) {
                $parentsLearnerUserIds = $learner->parents->pluck('user_id');
            }
            $parentsLearnerUserIds [] = auth()->id();

            $posts = Post::with(['books'=>function(MorphMany $query){
                    $query->with(['images','videos','audios','files','comments']);
                },'poems'=>function(MorphMany $query){
                    $query->with(['images','videos','audios','files','comments']);
                },'activities'=>function(MorphMany $query){
                    $query->with(['images','videos','audios','files','comments']);
                },'riddles'=>function(MorphMany $query){
                    $query->with(['images','videos','audios','files','answers']);
                },'questions'=>function(MorphMany $query){
                    $query->with(['images','videos','audios','files','answers']);
                },'comments'])->hasPostTypes()->withFilter()->hasPublished()
                ->hasNoFlags($parentsLearnerUserIds)->orderBy('updated_at', 'desc')
                ->paginate(5);

            return PostResource::collection($posts);            
        } catch (\Throwable $th) {
            // return response()->json([
            //     'message' => 'Unsuccessful. Something unexpected happened. Please try again later.',
            // ]);
            throw $th;
        }
    }

    public function posts(Request $request)
    {
        $posts = null;
        try {
            $posts = Post::hasNoApprovedFlags()->hasPostTypes()
                ->withFilter()->hasPublished()->orderBy('updated_at', 'desc')->paginate(5);

            return PostResource::collection($posts);            
        } catch (\Throwable $th) {
            // return response()->json([
            //     'message' => 'Unsuccessful. Something unexpected happened. Please try again later.',
            // ]);
            throw $th;
        }
    }

    public function postEdit(Request $request,$post, $account, $accountId)
    {
        $mainPost = Post::find($post);
        
        $mainAccount = getAccountObject($account,$accountId);

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
                    'score' => 'nullable',
                    'published' => 'nullable|date',
                    'previewFile' => 'nullable|file',
                    'previewFileType' => 'nullable|string',
                ]);

                $input['author'] = $request->author;
                $input['riddle'] = $request->riddle;

                if ((int)$request->score > 100) {
                    $input['score_over'] = 100;
                } else if (is_null($request->score) || (int)$request->score < 5) {
                    $input['score_over'] = 5;
                } else {
                    $input['score_over'] = (int) $request->score;
                }

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
                    'score' => 'nullable',
                    'published' => 'nullable|date',
                    'previewFile' => 'nullable|file',
                    'previewFileType' => 'nullable|string',
                ]);

                $input['question'] = $request->question;

                if ((int)$request->score > 100) {
                    $input['score_over'] = 100;
                } else if (is_null($request->score) || (int)$request->score < 5) {
                    $input['score_over'] = 5;
                } else {
                    $input['score_over'] = (int) $request->score;
                }

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

                $oldPossibleAnswers = $type->possibleAnswers;
                
                if ($request->has('possibleAnswers') && 
                    !is_null($request->possibleAnswers)) {
                    $possibleAnswers = json_decode($request->possibleAnswers);

                    if ($oldPossibleAnswers) {
                        $oldPossibleAnswers->forceDelete();
                    }

                    foreach ($possibleAnswers as $key => $possibleAnswer) {
                        $type->possibleAnswers()->create([
                            'option' => $possibleAnswer->option,
                        ]);
                    }
                }
            } 

            if ($type) {

                if ($request->hasFile('previewFile')) {
                    $fileDetails = getFileDetails($request->file('previewFile'));

                    accountCreateFile(
                        $mainAccount, 
                        $fileDetails,
                        $type
                    );
                }
            } else {
                DB::rollback();
                return response()->json([
                    'message' => 'unsuccessful',
                    'post' => $mainPost,
                ]);
            }
        }

        DB::commit();
        $mainPost = Post::with(['questions.images','questions.videos',
        'questions.audios','questions.files','activities.images','activities.videos',
        'activities.files','activities.audios','riddles.images','riddles.videos',
        'riddles.files','riddles.audios','poems.images','poems.videos',
        'poems.files','poems.audios','books.images','books.videos','books.files',
        'books.audios','postedby.profile'])->find($mainPost->id);
        Debugbar::info($mainPost);
        $postResource = new PostResource($mainPost);
        broadcast(new UpdatePost($postResource))->toOthers();
        return response()->json([
            'message' => 'successful',
            'post' => $postResource,
        ]);
    }

    public function postDelete($post, $account, $accountId)
    {
        $mainPost = Post::find($post);
        Debugbar::info($mainPost);
        
        try {
                
            $mainPost->delete();

            broadcast(new DeletePost([
                'postId' => $post,
                'account' => $account,
                'accountId' => $accountId,
            ]))->toOthers();
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

    public function postGet($post)
    {
        $item = null;
        $item = Post::with(['questions.images','questions.videos',
            'questions.audios','questions.files','activities.images','activities.videos',
            'activities.files','activities.audios','riddles.images','riddles.videos',
            'riddles.files','riddles.audios','poems.images','poems.videos',
            'poems.files','poems.audios','books.images','books.videos','books.files',
            'books.audios','postedby.profile'])->find($post);

        if (!$item) {
            return response()->json([
                'message' => 'unsuccessful. post does not exist.'
            ]);
        }
        return response()->json([
            'message' => 'successful',
            'post' => new PostResource($item),
        ]);
    }

    public function postsGet($account, $accountId)
    {
        $mainAccount = getAccountObject($account, $accountId);

        if (!$mainAccount) {
            return response()->json([
                'message' => 'unsuccessful. account does not exist.'
            ]);
        }

        return PostResource::collection($mainAccount->posts()
            ->with(['questions','activities','riddles',
            'poems','books','postedby.profile'])
            ->latest()->paginate(5));
    }
}
