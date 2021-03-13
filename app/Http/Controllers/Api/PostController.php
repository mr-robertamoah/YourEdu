<?php

namespace App\Http\Controllers\Api;

use App\DTOs\PostDTO;
use App\Http\Controllers\Controller;
use App\Http\Resources\DiscussionPostResource;
use App\Http\Resources\PostResource;
use App\Http\Requests\CreatePostRequest;
use App\Http\Requests\DeletePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Services\PostService;
use App\YourEdu\Discussion;
use App\YourEdu\Post;
use \Debugbar;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    public function createPost(CreatePostRequest $request)
    {
        DB::beginTransaction();
        
        $post = (new PostService)->createPost(
            PostDTO::createFromRequest($request)
        );

        DB::commit();

        return response()->json([
            'message' => 'successful',
            'post' => new PostResource($post),
        ]);
    }

    public function getUserPosts(Request $request)
    {
        ray($request)->green();
        $parentsLearnerUserIds = [];
        $learner = auth()->user()->learner;
        try {
            if ($learner && $learner->parents) {
                $parentsLearnerUserIds = $learner->parents->pluck('user_id');
                $parentsLearnerUserIds[] = $learner->user_id;
            }

            $items = Post::with(['books'=>function(MorphMany $query){
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
                ->hasNoFlags($parentsLearnerUserIds)
                ->get();

            $items = $items->merge(Discussion::notSocial()->with([
                'images','videos','audios','files','comments','flags','attachments',
                'beenSaved','messages','raisedby.profile','requests.requestfrom'])
                ->get());
                           
            return DiscussionPostResource::collection(paginate($items->sortByDesc('updated_at'), 5));            
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

    public function updatePost(UpdatePostRequest $request)
    {
        DB::beginTransaction();

        $post = (new PostService)->updatePost(
            PostDTO::createFromRequest($request)
        );

        DB::commit();
        
        return response()->json([
            'message' => 'successful',
            'post' => new PostResource($post),
        ]);
    }

    public function deletePost(DeletePostRequest $request)
    {
        ray($request)->green();
        (new PostService)->deletePost(
            PostDTO::createFromRequest($request)
        );
                   
        return response()->json([
            'message' => "successful"
        ]);
    }

    public function postGet($post)
    {
        $item = null;
        $item = Post::with(['questions.images','questions.videos',
            'questions.audios','questions.files','activities.images','activities.videos',
            'activities.files','activities.audios','riddles.images','riddles.videos',
            'riddles.files','riddles.audios','poems.images','poems.videos',
            'poems.files','poems.audios','books.images','books.videos','books.files',
            'books.audios','addedby.profile','lessons.images','lessons.videos',
            'lessons.files','lessons.audios'])->find($post);

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
        $mainAccount = getYourEduModel($account, $accountId);

        if (!$mainAccount) {
            return response()->json([
                'message' => 'unsuccessful. account does not exist.'
            ]);
        }

        $items = $mainAccount->posts()
            ->with(['questions.images','questions.videos',
            'questions.audios','questions.files','activities.images','activities.videos',
            'activities.files','activities.audios','riddles.images','riddles.videos',
            'riddles.files','riddles.audios','poems.images','poems.videos',
            'poems.files','poems.audios','books.images','books.videos','books.files',
            'books.audios','addedby.profile','lessons.images','lessons.videos',
            'lessons.files','lessons.audios'])->get();
        
        $items = $items->merge($mainAccount->discussions()->notSocial()->with([
            'images','videos','audios','files','likes','comments','messages',
            'attachments','participants','beenSaved','flags','raisedby.profile',
        ])->get());

        return DiscussionPostResource::collection(paginate($items->sortByDesc('updated_at'), 5));
    }
}
