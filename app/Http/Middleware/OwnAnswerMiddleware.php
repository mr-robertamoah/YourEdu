<?php

namespace App\Http\Middleware;

use App\YourEdu\Answer;
use Closure;

class OwnAnswerMiddleware
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
        $mainAnswer = null;
        $mainAnswer = Answer::find($request->route('answerId'));

        if (!$mainAnswer) {
            return response()->json([
                'message' => "unsuccessful. answer not found."
            ]);
        }

        if ($mainAnswer->answeredby->user_id !== auth()->id()) {
            return response()->json([
                'message' => "unsuccessful. you do not own this answer."
            ]);
        }
        return $next($request);
    }
}
