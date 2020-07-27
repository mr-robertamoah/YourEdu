<?php

namespace App\Http\Middleware;

use App\YourEdu\Comment;
use Closure;

class OwnCommentMiddleware
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
        $mainComment = null;
        $mainComment = Comment::find($request->route('comment'));
        
        if(!$mainComment){
            return response()->json([
                'message' => "unsuccessful. post not found."
            ]);
        }

        if($mainComment->commentedby->user_id !== auth()->id()){
            return response()->json([
                'message' => "unsuccessful. you do not own this post."
            ]);
        }
        return $next($request);
    }
}
