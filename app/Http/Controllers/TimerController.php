<?php

namespace App\Http\Controllers;

use App\DTOs\TimerDTO;
use App\Http\Resources\TimerResource;
use App\Services\TimerService;
use Illuminate\Http\Request;

class TimerController extends Controller
{
    public function createTimer(Request $request)
    {
        try {
            $timer = (new TimerService)->createTimer(
                TimerDTO::createFromRequest($request)
            );

            return response()->json([
                'message' => 'successful',
                'timer' => $timer ? new TimerResource($timer) : null
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
