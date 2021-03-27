<?php

namespace App\Http\Controllers\Api;

use App\DTOs\WorkDTO;
use App\Http\Controllers\Controller;
use App\Services\WorkService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WorkController extends Controller
{
    public function submitWork(Request $request)
    {
        $work = (new WorkService)->submitWork(
            WorkDTO::createFromRequest($request)
        );

        DB::commit();
        response()->json([
            'message' => 'successful',
        ]);
    }
}
