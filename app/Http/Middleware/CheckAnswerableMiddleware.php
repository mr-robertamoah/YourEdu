<?php

namespace App\Http\Middleware;

use Closure;

class CheckAnswerableMiddleware
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
        if ($request->route('item') !== 'riddle' &&
            $request->route('item') !== 'question') {
            return response()->json([
                'message' => "unsuccessful, {$request->route('item')} is not a valid item"
            ]);
        }
        
        return $next($request);
    }
}
