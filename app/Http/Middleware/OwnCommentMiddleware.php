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
                'message' => "unsuccessful. comment not found."
            ]);
        }

        if (class_basename_lower($mainComment->commentedby) === 'school') {
            if (!in_array(auth()->id(),$mainComment->commentedby->getAdminIds())) {
                return response()->json([
                    'message' => "unsuccessful. you are not authorized to perform this action."
                ],422);
            }
        } else if($mainComment->commentedby->user_id !== auth()->id()){
            return response()->json([
                'message' => "unsuccessful. you do not own this comment."
            ],422);
        }
        return $next($request);
    }
}
