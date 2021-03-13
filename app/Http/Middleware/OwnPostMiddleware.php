<?php

namespace App\Http\Middleware;

use App\YourEdu\Post;
use Closure;

class OwnPostMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $mainPost = Post::find($request->route('postId'));
        
        if(!$mainPost){
            return response()->json([
                'message' => "unsuccessful. post not found."
            ],401);
        }

        if($mainPost->addedby->user_id !== auth()->id()){
            return response()->json([
                'message' => "unsuccessful. you do not own this post."
            ],401);
        }
        return $next($request);
    }
}
