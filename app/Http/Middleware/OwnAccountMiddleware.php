<?php

namespace App\Http\Middleware;

use App\YourEdu\Facilitator;
use App\YourEdu\Learner;
use App\YourEdu\ParentModel;
use App\YourEdu\Professional;
use App\YourEdu\Profile;
use App\YourEdu\School;
use Closure;
use Illuminate\Support\Facades\Auth;

class OwnAccountMiddleware
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
        $profileOwner = null;
        $profile = null;
        $id = Auth::id();

        if($request->has('account')){
            if ($request->account === 'learner') {
                $profileOwner = Learner::where('user_id',$id);
            } else if ($request->account === 'parent') {
                $profileOwner = ParentModel::where('user_id',$id);
            } else if ($request->account === 'facilitator') {
                $profileOwner = Facilitator::where('user_id',$id);
            } else if ($request->account === 'school') {
                $profileOwner = School::where('user_id',$id);                
            } else if ($request->account === 'professional') {
                $profileOwner = Professional::where('user_id',$id);
            }
            
            if (is_null($profileOwner)) {
                return response()->json([
                    'message' => "no such {$request->account} exists"
                ],422);
            }

            if (!is_null($request->route('profile')) ) {
                $profile = Profile::find($request->route('profile'));
            }

            // return response()->json([
            //     'user' => $id,
            //     'profile' => $profile->user_id != $id,
            //     'profile_owner' =>$profile->user_id,
            // ],422);

            if (is_null($profile) ){
                return response()->json([
                    'message' => "profile not found"
                ],422);
            }

            if ($profile->user_id != $id){
                return response()->json([
                    'message' => "you are not the {$request->account} to update this account"
                ],422);
            }
        } else {
            return response()->json([
                'message' => 'inadequate data for updating profile'
            ],422);
        }
        
        return $next($request);
    }
}
