<?php

namespace App\Http\Middleware;

use Closure;

class CheckItemMiddleware
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
        // dd($request);
        $item = $request->route('item');
        $itemId = $request->route('itemId');
        
        if ($item) {
            if ($item === 'post' ||
                $item === 'lesson' ||
                $item === 'comment' ||
                $item === 'summary' ||
                $item === 'class' ||
                $item === 'request' ||
                $item === 'admission' ||
                $item === 'ban' ||
                $item === 'flag' ||
                $item === 'keyword' ||
                $item === 'word' ||
                $item === 'expression' ||
                $item === 'read' ||
                $item === 'discussion' ||
                $item === 'character' ||
                $item === 'school') {
            } else {
                return response()->json([
                    'status' => false,
                    'item' => $item,
                    'message' => "Unsuccessful. You cannot comment on or like {$item}.",
                ], 422);
            }

            if (!intval($itemId)) {
                return response()->json([
                    'status' => false,
                    'itemId' => $itemId,
                    'message' => "Unsuccessful. {$item} id must be a number and not a text.",
                ], 422);
            }    
        }
        
        return $next($request);
    }
}
