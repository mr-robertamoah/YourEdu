<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateAccountRequest;
use App\Http\Resources\OwnedProfileResource;
use App\Http\Resources\UserAccountResource;
use App\Http\Resources\UserSearchResource;
use App\Services\AccountService;
use Carbon\Carbon;
use \Debugbar;
use Illuminate\Support\Facades\DB;

class YourEduController extends Controller
{
    //
//////////////////////////////////////////////////////////
    
/////////////////////////////////////////////////////////////////
    
///////////////////////////////////////////////////////////////////

    public function index ()
    {
        return view('youredu');
    }

    public function create(CreateAccountRequest $request)
    {
        try {
            DB::beginTransaction();
            if ($request->creator === 'user') {

                $account = (new AccountService())->createMainAccount([
                    'create' => $request->create,
                    'user' => auth()->user(),
                    'name' => $request->name,
                    'role' => $request->role,
                    'classStructure' => $request->classStructure,
                    'description' => $request->description,
                    'otherName' => $request->other_name,
                    'about' => $request->about,
                    'types' => $request->types,
                ]);

                DB::commit();
                return response()->json([
                    'message' => 'successful',
                    'profile' => new OwnedProfileResource($account->profile),
                ]);
            } else {
                $id = auth()->id();
                $userInputOne = [
                    'first_name' => $request->new_first_name,
                    'last_name' => $request->new_last_name,
                    'other_names' => $request->new_other_names,
                    'username' => $request->new_username,
                    'email' => $request->new_email,
                    'referrer_id' => $id,
                    'gender' => $request->new_gender,
                    'dob' => Carbon::parse($request->new_dob),
                    'password' => bcrypt($request->new_password),
                ];
    
                $accountInputOne = [
                    'name' => $request->name,
                    'title' => $request->title,
                    'level' => $request->level,
                    'files' => $request->file('files'),
                    'role' => null,
                    'salary' => $request->salary,
                    'salaryPeriod' => $request->salaryPeriod,
                    'currency' => $request->currency,
                    'description' => $request->description,
                    'otherName' => null,
                ];
                Debugbar::info($request->file('files'));
    
                $userInputTwo = null;
                $accountInputTwo = null;
                if (json_decode($request->parent)) {
                    $userInputTwo = [
                        'first_name' => $request->parent_first_name,
                        'last_name' => $request->parent_last_name,
                        'other_names' => $request->parent_other_names,
                        'username' => $request->parent_username,
                        'referrer_id' => $id,
                        'email' => $request->parent_email,
                        'gender' => $request->parent_gender,
                        'dob' => $request->parent_dob,
                        'password' => bcrypt($request->parent_password),
                    ];
                    
                    $accountInputTwo = [
                        'name' => $request->parent_name,
                        'role' => null,
                        'description' => null,
                        'otherName' => null,
                    ];             
                }
                
                $accountsData = (new AccountService())->createUserAccount($userInputOne,
                    $accountInputOne,$request->create,$request->creator,$request->creatorId,
                    $request->parent_role,$userInputTwo,$accountInputTwo);
    
                DB::commit();
                return response()->json([
                    'message' => 'successful',
                    'userOne' => new UserSearchResource($accountsData['userOne']),
                    'accountOne' => is_null($accountsData['accountOne']) ?  null :
                        new UserAccountResource($accountsData['accountOne']),
                    'userTwo' => is_null($accountsData['userTwo']) ?  null :
                        new UserSearchResource($accountsData['userTwo']),
                    'accountTwo' => is_null($accountsData['accountTwo']) ?  null : 
                        new UserAccountResource($accountsData['accountTwo']),
                ]);
            }

        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
        
        // if ($request->create === 'group') {
        //     if ($request->creator === 'learner') {
                
        //     } else if ($request->creator === 'professional') {
                
        //     } else if ($request->creator === 'facilitator') {
                
        //     } else if ($request->creator === 'parent') {
                
        //     } else if ($request->creator === 'school') {
                
        //     }
            
        // }
    }
}
