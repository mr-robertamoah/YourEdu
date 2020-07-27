<?php

namespace App\Http\Middleware;

use App\YourEdu\Like;
use Closure;

class OwnLikeMiddleware
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
        $like = Like::find($request->route('like'));
        // dd($request->route('like'));
        if ($like) {
            if ($like->user_id !== auth()->id()) {
                return response()->json([
                    'message' => 'you cannot unlike a like you do not own.'
                ]);
            }
        } else {
            return response()->json([
                'message' => 'there is no such like.'
            ]);
        }

        return $next($request);
    }
}
