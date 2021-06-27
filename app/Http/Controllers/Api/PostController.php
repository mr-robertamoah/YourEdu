<?php

namespace App\Http\Controllers\Api;

use App\DTOs\PostDTO;
use App\DTOs\PostsDTO;
use App\Http\Controllers\Controller;
use App\Http\Resources\HomeItemResource;
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
        try {
            $items = (new PostService)->getUserPosts(
                PostsDTO::createFromRequest($request)
            );

            return HomeItemResource::collection($items);
        } catch (\Throwable $th) {
            // return response()->json([
            //     'message' => 'Unsuccessful. Something unexpected happened. Please try again later.',
            // ]);
            throw $th;
        }
    }

    public function posts(Request $request)
    {
        try {
            $items = (new PostService)->getPosts(
                PostsDTO::createFromRequest($request)
            );

            return HomeItemResource::collection($items);
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
        $item = Post::with([
            'questions.images', 'questions.videos',
            'questions.audios', 'questions.files', 'activities.images', 'activities.videos',
            'activities.files', 'activities.audios', 'riddles.images', 'riddles.videos',
            'riddles.files', 'riddles.audios', 'poems.images', 'poems.videos',
            'poems.files', 'poems.audios', 'books.images', 'books.videos', 'books.files',
            'books.audios', 'addedby.profile', 'lessons.images', 'lessons.videos',
            'lessons.files', 'lessons.audios'
        ])->find($post);

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

    public function postsGet(Request $request)
    {
        try {
            $items = (new PostService)->getItems(
                PostsDTO::createFromRequest($request)
            );
        } catch (\Throwable $th) {
            throw $th;
            response()->json([
                'message' => "oops! something happened 😕"
            ]);
        }

        return HomeItemResource::collection($items);
    }
}
