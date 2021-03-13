<?php

namespace App\Http\Middleware;

use App\YourEdu\Facilitator;
use App\YourEdu\Learner;
use App\YourEdu\ParentModel;
use App\YourEdu\Professional;
use App\YourEdu\School;
use Closure;

class CheckAccountMiddleware
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
        $main = null;
        $account = $request->route('account');
        $accountId = $request->route('accountId');

        if ($account) {
            if ($account === 'learner' ||
                $account === 'parent' ||
                $account === 'facilitator' ||
                $account === 'school' ||
                $account === 'professional') {
            } else {
                return response()->json([
                    'status' => false,
                    'account' => $account,
                    'message' => "Unsuccessful. Account cannot be anything apart from learner, parent, facilitator, professional and school.",
                ], 422);
            }

            if (!intval($accountId)) {
                return response()->json([
                    'status' => false,
                    'accountId' => $accountId,
                    'message' => "Unsuccessful. Account id must be a number and not a text.",
                ], 422);
            }

            if ($account === 'learner') {
                $main = Learner::find($accountId);
            } else if ($account === 'parent') {
                $main = ParentModel::find($accountId);
            } else if ($account === 'facilitator') {
                $main = Facilitator::find($accountId);
            } else if ($account === 'school') {
                $main = School::find($accountId);
            } else if ($account === 'professional') {
                $main = Professional::find($accountId);
            }

            if (!$main) {
                return response()->json([
                    'status' => false,
                    'message' => "Unsuccessful. such {$account} does not exist.",
                ], 422);
            }

        }
        
        return $next($request);
    }
}
