<?php

namespace App\Http\Controllers\Api;

use App\Events\TestEvent;
use App\Http\Controllers\Controller;
use App\Http\Resources\FacilitatorResource;
use App\Http\Resources\LearnerResource;
use App\Http\Resources\ParentModelResource;
use App\Http\Resources\ProfessionalResource;
use App\Http\Resources\ProfileResource;
use App\Http\Resources\SchoolResource;
use App\User;
use App\YourEdu\Facilitator;
use App\YourEdu\Learner;
use App\YourEdu\ParentModel;
use App\YourEdu\Professional;
use App\YourEdu\Profile;
use App\YourEdu\School;
use Carbon\Carbon;
use Illuminate\Http\Request ;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class YourEduController extends Controller
{
    //
//////////////////////////////////////////////////////////
    
/////////////////////////////////////////////////////////////////
    
///////////////////////////////////////////////////////////////////

    public function index ()
    {
        // broadcast(new TestEvent('hello world'));
        return view('youredu');
    }

    private function ageInappropriateMessage($who = 'user',$school = false)
    {
        if ($school) {
            return 'You must be at least 18, to be able to own a school on this platform.';
        } else {
            return "You must be at least 18 years to be a {$who}. If you meet this requirement, then update your date of birth on the welcome or profile page";
        }
    }

    public function userCreate($request, $parent = false)
    {
        $input = [];
        $input['first_name'] = $request->new_first_name;
        $input['last_name'] = $request->new_last_name;
        $input['other_names'] = $request->new_other_names;
        $input['username'] = $request->new_username;
        $input['email'] = $request->new_email;
        $input['dob'] = Carbon::parse($request->new_dob);
        $input['password'] = $request->new_password;
        $input['password_confirmation'] = $request->new_password_confirmation;

        $validator = Validator::make($input,[
            'first_name' => 'string',
            'last_name' => 'string',
            'other_names' => 'nullable|string',
            'username' => 'required|alpha_num|unique:users,username|min:8',
            'email' => 'nullable|email|unique:users,email',
            'dob' => 'required|date',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required',
        ]);

        if ($validator->fails()) {
            return [
                'status' => false,
                'errors' => $validator->errors(),
            ];
        }

        $userNew = User::create($input);

        if($parent){
        
            $input = [];
            $input['first_name'] = $request->parent_first_name;
            $input['last_name'] = $request->parent_last_name;
            $input['other_names'] = $request->parent_other_names;
            $input['username'] = $request->parent_username;
            $input['email'] = $request->parent_email;
            $input['dob'] = $request->parent_dob;
            $input['password'] = $request->parent_password;
            $input['password_confirmation'] = $request->parent_password_confirmation;

            $validator = Validator::make($input,[
                'first_name' => 'string',
                'last_name' => 'string',
                'other_names' => 'nullable|string',
                'username' => 'string|unique:users,username|min:8',
                'email' => 'nullable|email|unique:users,email',
                'dob' => 'required|date',
                'password' => 'required|string|min:8|confirmed',
                'password_confirmation' => 'required|string',
            ]);

            if ($validator->fails()) {
                return [
                    'status' => 'parent',
                    'errors' => $validator->errors(),
                ];
            }

            $userNewParent = User::create($input);

            return [
                'learner' => $userNew, 
                'parent' => $userNewParent, 
                'errors' => null,
            ];
        } else{
            return [
                'new' => $userNew, 
                'errors' => null
            ];
        }
        
    }

    public function create(Request $request)
    {

        $request->validate([
            'create' => 'string',
            'creator' => 'string',
            'user_id' => 'integer',
            'school_id' => 'nullable|integer',
            'other_id' => 'nullable|integer',
            'other_username' => 'nullable|string',
            'parent_role' => 'nullable|string',
            'name' => 'nullable|string',
            'email' => 'nullable|email',
            'school_type' => 'nullable|string',
            'company_name' => 'string',
            'description' => 'nullable|string',
            'relationship' => 'nullable|string',
            'relationship_description' => 'nullable|string',
        ]);

        if ($request->create === 'learner') {
            if ($request->creator === 'parent') {

                $parent = null;
                $learner = null;

                if (auth()->id()) {
                    $learner = Learner::find(auth()->id());
                } else if ($request->username) {
                    $learner = User::where('username',$request->username)->first()->learner;
                }
                
                if ($request->other_id) {
                    $parent = ParentModel::find($request->other_id);
                } else if ($request->other_username) {
                    $parent = User::where('username',$request->other_username)->first()->parent;
                } 
                
                try {
                    
                    DB::beginTransaction();

                    if ($request->other_user_id && !$parent) {
                        $userParent = User::find($request->other_user_id);
                        
                        if (!$userParent->dob || now()->diffInYears($userParent->dob) < 18 || $userParent->dob->year === $userParent->created_at->year) {
                            return response()->json([
                                'status' => false,
                                'message' => $this->ageInappropriateMessage('parent')
                            ]);
                        }

                        $parent = $userParent->parent()->create([
                            'name' => $userParent->full_name
                        ]);
                    }
    
                    if (! $learner) {
                        
                        $userLearnerArray = $this->userCreate($request);

                        if ($userLearnerArray['errors']) {
                            return response()->json([
                                'status' => $userLearnerArray['status'],
                                'errors' => $userLearnerArray['errors']
                            ]);
                        }

                        $userLearner = $userLearnerArray['new'];

                        if ($userLearner->learner()->exists()) {
                            return response()->json([
                                'status' => false,
                                'message' => 'You are already a learner. You can do may things with the same learner account'
                            ]);
                        }
    
                        $learner = $userLearner->learner()->create([
                            'name' => $userLearner->full_name
                        ]);
                    }
    
                    if ($learner && $parent) {

                        if ($request->parent_role && ($request->parent_role === 'FATHER' || $request->parent_role === 'MOTHER')) {
                            $parentRole = $request->parent_role;
                        } else {
                            $parentRole = 'GUARDIAN';
                        }
    
                        $parent->learners()->attach($learner->id,[
                            'role'=> $parentRole
                        ]);
    
                        DB::commit();
                        return response()->json([
                            'status'=> (bool) $learner,
                            'learner'=> $learner,
                        ]);
                    }
                    DB::rollback();
                    return response()->json([
                        'status'=> (bool) $learner,
                        'learner'=> $learner,
                        'parent'=> $parent,
                        'message'=> 'unsuccessful',
                    ],401);
                } catch (\Throwable $th) {
                    DB::rollback();
                    throw $th;
                }
                
            } else if ($request->creator === 'school') {

                $learner = null;
                $parent = null;
                
                if ($request->other_id) {
                    $parent = ParentModel::find($request->other_id);
                } else if ($request->other_username) {
                    $parent = User::where('username',$request->other_username)->first()->parent;
                } 
                
                try {
                    DB::beginTransaction();

                    if ($parent) {
                        $userLearner = $this->userCreate($request);
                    } else {
                        $userLearnerParentArray = $this->userCreate($request,true);

                        if ($userLearnerParentArray['errors']) {
                            return response()->json([
                                'status' => $userLearnerParentArray['status'],
                                'errors' => $userLearnerParentArray['errors']
                            ]);
                        }
                        
                        $userLearner = $userLearnerParentArray['learner'];
                        $userParent = $userLearnerParentArray['parent'];
                        // list($userLearner, $userParent) = $userLearnerParentArray;
                            
                        if (!$userParent->dob || now()->diffInYears($userParent->dob) < 18 || $userParent->dob->year === $userParent->created_at->year) {
                            return response()->json([
                                'status' => false,
                                'message' => $this->ageInappropriateMessage('parent')
                            ]);
                        }

                        if ($userParent->parent()->exists()) {
                            return response()->json([
                                'status' => false,
                                'message' => 'You are already a parent. You can add his/her parent account to your school'
                            ]);
                        }

                        $parent = $userParent->parent()->create([
                            'name' => $userParent->full_name,
                        ]);

                        if ($userLearner->learner()->exists()) {
                            return response()->json([
                                'status' => false,
                                'message' => 'User is already a learner. You can add his/her learner account to your school'
                            ]);
                        }

                        $learner = $userLearner->learner()->create([
                            'name' => $userLearner->full_name,
                        ]);

                    }

                    if ($parent && $learner) {

                        if ($request->parent_role && ($request->parent_role === 'FATHER' || $request->parent_role === 'MOTHER')) {
                            $parentRole = $request->parent_role;
                        } else {
                            $parentRole = 'GUARDIAN';
                        }

                        $parent->learners()->attach($learner->id,[
                            'role'=> $parentRole,
                        ]);

                        $school = School::find($request->school_id);
                        if ($school) {

                            if ($school->role === 'VIRTUAL' || !$request->type || $request->type != 'TRADITIONAL' || $request->type != 'OTHER') {
                                $type = 'VIRTUAL';
                            } else {
                                $type = $request->type;
                            }
                            $school->learners()->attach($learner,['type'=> $type]);

                            DB::commit();
                            return response()->json([
                                'status'=> true,
                                'learner'=> $learner,
                                'parent'=> $parent,
                            ]);
                        } 
                    }
                    
                    DB::rollback();
                    return response()->json([
                        'status'=> false,
                        'learner'=> $learner,
                        'parent'=> $parent,
                        'message'=> 'unsuccessful',
                    ],401);
                } catch (\Throwable $th) {
                    DB::rollback();
                    throw $th;
                }
                
            } else if ($request->creator === 'user') {
                //tested
                
                $userLearner = User::find(auth()->id());
                $learner = null;
                $learnerProfile = [];
                ///////change my mind on the 'you require a parent to be learner' policy
                // $user = User::find(auth()->id());
                // $parent = null;

                // if ($request->other_id) {
                //     $parent = ParentModel::find($request->other_id);
                // } else if ($request->other_username) {
                //     $parent =User::where('username',$request->other_username)->first()->parent;
                // } else {
                //     $parent = $user->parent;
                // }
                
                try {
                    DB::beginTransaction();

                    // if ((!$user->dob || now()->diffInYears($user->dob, true) < 18 || $user->dob->year === $user->created_at->year) && !$parent) {
                        
                    //     $userParentArray = $this->userCreate($request);
                    //     if ($userParentArray['errors']) {
                    //         return response()->json([
                    //             'status' => $userParentArray['status'],
                    //             'errors' => $userParentArray['errors']
                    //         ]);
                    //     }
                    //     $userParent = $userParentArray['new'];

                    //     if (!$userParent->dob || now()->diffInYears($userParent->dob, true) < 18 || $userParent->dob->year === $userParent->created_at->year) {
                    //         $parent = $userParent->parent()->create([
                    //             'name' => $userParent->full_name
                    //         ]);
                    //     }

                    //     if (!$parent) {
                    //         DB::rollback();
                    //         return response()->json([
                    //             'status' => false,
                    //             'message' => 'You require a parent. If you are above 18, please do indicate it by editing your user details in the welcome page.'
                    //         ]);
                    //     }
                    // }

                    if ($request->name ==='') {
                        $inputName =  $userLearner->full_name;
                    } else {
                        $inputName = $request->name;
                    }

                    if ($userLearner) {

                        if ($userLearner->has('learner')) {
                            DB::rollback();
                            return response()->json([
                                'status' => false,
                                'message' => "You cannot be more than one learner"
                            ],422);
                        }

                        // if ($userLearner->has('parent')) {
                        //     DB::rollback();
                        //     return response()->json([
                        //         'status' => false,
                        //         'userLearner' => "You are a parent. You can't be a learner too" //need to make a decision on whether you can be a parent and a learner
                        //     ]);
                        // }
                        $learner = $userLearner->learner()->create([
                            'name' => $inputName
                        ]);

                        // if ($parent) {
                        //     $parent->learners()->attach($learner->id,[
                        //         'role' => $request->parent_role
                        //     ]);
                        // }

                        $learnerProfile['account_id'] = $learner->id;
                        $learnerProfile['account_type'] = $request->create;
                        $learnerProfile['profile'] = 'App\YourEdu\Learner';
                        $learnerProfile['profile_name'] = $learner->name;
                        $learnerProfile['profile_url'] = $learner->profile->url;

                        DB::commit();
                        return response()->json([
                            'message' => 'successful',
                            'learner' => $learner,
                            'owned_profile' => $learnerProfile,
                        ]);
                    } else{
                        DB::rollback();
                        return response()->json([
                            'status' => (bool) $userLearner,
                            'user' => 'user not found'
                        ]);
                    }

                    // DB::commit();
                    // return response()->json([
                    //     'status' => (bool) $learner,
                    //     'learner' => $learner
                    // ]);
                } catch (\Throwable $th) {
                    DB::rollback();
                    throw $th;
                }
            }
        } else if ($request->create === 'facilitator') {
            if ($request->creator === 'school') {
                
                $facilitator = null;
                $school = null;
                $userFacilitator = null;

                if ($request->other_id) {
                    $facilitator = Facilitator::find($request->other_id);
                }

                try {
                    if (! $facilitator) {

                        DB::beginTransaction();

                        $userFacilitatorArray = $this->userCreate($request);

                        if ($userFacilitatorArray['errors']) {
                            return response()->json([
                                'status' => $userFacilitatorArray['status'],
                                'errors' => $userFacilitatorArray['errors']
                            ]);
                        }

                        $userFacilitator = $userFacilitatorArray['new'];

                        if (now()->diffInYears($userFacilitator->dob) < 18 ||  $userFacilitator->dob->year === $userFacilitator->created_at->year) {
                            
                            DB::rollback();
                            return response()->json([
                                'status' => false,
                                'facilitator' => $this->ageInappropriateMessage('facilitator')
                            ]);
                        }

                        if ($userFacilitator->facilitator()->exists()) {
                            return response()->json([
                                'status' => false,
                                'message' => 'User is already a facilitator. You can just add his/her account to your school'
                            ]);
                        }

                        $facilitator = $userFacilitator->facilitator()->create([
                            'name' => $userFacilitator->full_name
                        ]);
                    } 
                    
                    if ($facilitator) {

                        $school = School::find($request->school_id);
                        
                        if ($school) {

                            if (!$request->relationship || $school->role === 'VIRTUAL') {
                                $relationship = 'VIRTUAL';
                            } else {
                                $relationship = $request->relationship;
                            }

                            $school->facilitators()->save($facilitator,[
                                'relationship' => $relationship,
                                'relationship_description' => $request->relationship_description,
                            ]);

                            DB::commit();
                            return response()->json([
                                'status' => (bool) $facilitator,
                                'facilitator' => $facilitator
                            ]);
                        }
                    }

                    DB::rollback();
                    return response()->json([
                        'status' => false,
                        'facilitator' => $facilitator,
                        'school' => $school,
                        'message' => 'unsuccessful',
                    ], 401);
                } catch (\Throwable $th) {
                    DB::rollback();
                    throw $th;
                }
            
            } else if ($request->creator === 'user') {
                //tested
                $user = User::find(auth()->id());
                $facilitator = null;
                $facilitatorProfile = [];
                try {
                    DB::beginTransaction();
                    if ($user) {

                        if (!$user->dob || now()->diffInYears($user->dob) < 18  ||  $user->dob->year === $user->created_at->year) {
                            return response()->json([
                                'status' => (bool) $user,
                                'message' => $this->ageInappropriateMessage('facilitator')
                            ],422);
                        }

                        if ($user->facilitator()->exists()) {
                            return response()->json([
                                'status' => false,
                                'message' => 'You are already a facilitator. You can do may things with the same facilitator account'
                            ]);
                        }
                        
                        if ($request->name ==='') {
                            $inputName =  $user->full_name;
                        }else{
                            $inputName =  $request->name;
                        }
    
                        $facilitator = $user->facilitator()->create(['name' => $inputName]);
                    } else{
                        return response()->json([
                            'status' => (bool) $user,
                            'message' => 'user not found'
                        ],422);
                    }

                    $facilitatorProfile['account_id'] = $facilitator->id;
                    $facilitatorProfile['account_type'] = $request->create;
                    $facilitatorProfile['profile'] = 'App\YourEdu\Facilitator';
                    $facilitatorProfile['profile_name'] = $facilitator->name;
                    $facilitatorProfile['profile_url'] = $facilitator->profile->url;
    
                    DB::commit();
                    return response()->json([
                        'status' => (bool) $facilitator,
                        'facilitator' => $facilitator,
                        'owned_profile' => $facilitatorProfile,
                    ]);
                } catch (\Throwable $th) {
                    DB::rollback();
                    throw $th;
                }
            
            }
        } else if ($request->create === 'professional') {
            if ($request->creator==='user') {
                //tested 
                $user = User::find(auth()->id());
                $professional = null;
                $professionalProfile = [];

                try {
                    if ($user) {

                        if (!$user->dob || now()->diffInYears($user->dob) < 18 ||  $user->dob->year === $user->created_at->year) {
                            return response()->json([
                                'status' => (bool) $user,
                                'message' => $this->ageInappropriateMessage('professional')
                            ],422);
                        }

                        DB::beginTransaction();
                        if ($request->name ==='') {
                            $inputName =  $user->full_name;
                        }else{
                            $inputName =  $request->name;
                        }
                        
                        if ($request->role ==='') {
                            $inputRole =  'TRAINER';
                        }else{
                            $inputRole =  $request->role;
                        }

                        if ($request->role ==='OTHER') {
                            $inputOther =  $request->other_name;
                        }else{
                            $inputOther =  null;
                        }
                        
                        if ($user->professionals()->where('role',$inputRole)->first()) {
                            return response()->json([
                                'status' => false,
                                'message' => 'You already have a professional account with this role. Please use another role for this new one.'
                            ],422);
                        }

                        $professional = $user->professionals()->create([
                            'name' => $inputName, 
                            'role' => $inputRole,
                            'other_name' => $inputOther,
                            'description' => $request->description,
                        ]);

                        $professionalProfile['account_id'] = $professional->id;
                        $professionalProfile['account_type'] = $request->create;
                        $professionalProfile['profile'] = 'App\YourEdu\Professional';
                        $professionalProfile['profile_name'] = $professional->name;
                        $professionalProfile['profile_url'] = $professional->profile->url;
                    } else{
                        return response()->json([
                            'status' => (bool) $user,
                            'user' => 'user not found'
                        ]);
                    }
    
                    DB::commit();
                    return response()->json([
                        'status' => (bool) $professional,
                        'professional' => $professional,
                        'owned_profile' => $professionalProfile,
                    ]);
                } catch (\Throwable $th) {
                    DB::rollback();
                    throw $th;
                }
            } else if ($request->creator === 'school') {
                // tested
                $professional = null;
                $school = null;

                if ($request->other_id) {
                    $professional = Professional::find($request->other_id);
                }

                try {
                    DB::beginTransaction();

                    if (! $professional) {
                        $userProfessionalArray = $this->userCreate($request);

                        if ($userProfessionalArray['errors']) {
                            return response()->json([
                                'status' => $userProfessionalArray['status'],
                                'errors' => $userProfessionalArray['errors']
                            ]);
                        }

                        $userProfessional = $userProfessionalArray['new'];
    
                        if (now()->diffInYears($userProfessional->dob) < 18  ||  $userProfessional->dob->year === $userProfessional->created_at->year) {
                            DB::rollback();
                            return response()->json([
                                'status' => false,
                                'professional' => $this->ageInappropriateMessage('professional')
                            ]);
                        }
                        $professional = $userProfessional->professionals()->create([
                            'name' => $userProfessional->full_name
                        ]);
                    }
                    
                    if ($professional) {
    
                        $school = School::find($request->school_id);
                        
                        if ($school) {

                            if (!$request->relationship || $school->role === 'VIRTUAL') {
                                $relationship = 'VIRTUAL';
                            } else {
                                $relationship = $request->relationship;
                            }

                            $school->professionals()->save($professional,[
                                'relationship' => $relationship,
                                'relationship_description' => $request->relationship_description,
                            ]);
    
                            DB::commit();
                            return response()->json([
                                'status' => (bool) $professional,
                                'professional' => $professional
                            ]);
                        }
                    }
    
                    DB::rollback();
                    return response()->json([
                        'status' => false,
                        'professional' => $professional,
                        'school' => $school,
                        'message' => 'unsuccessful',
                    ], 401);
                } catch (\Throwable $th) {
                    DB::rollback();
                    throw $th;
                }
            }
        } else if ($request->create === 'school') {
            //tested
            $user = User::find(auth()->id());
            $schoolProfile = [];
            try {
                if ($user) {
                    DB::beginTransaction();

                    if (now()->diffInYears($user->dob) < 18  ||  $user->dob->year === $user->created_at->year) {
                        DB::rollback();
                        return response()->json([
                            'status' => false,
                            'message' => $this->ageInappropriateMessage('',true)
                        ]);
                    }

                    if ($user->schools()->where('company_name',$request->company_name)->first()) {
                        return response()->json([
                            'status' => false,
                            'message' => 'You already have a school account with this company name.'
                        ],422);
                    }

                    if ($request->has('role') && $request->role === 'TRADITIONAL') {
                        $inputRole = $request->role;
                    }else{
                        $inputRole = 'VIRTUAL';
                    }
    
                    $school = $user->schools()->create([
                        'company_name' => $request->company_name,
                        'role' => $inputRole,
                    ]);
    
                    if ($school) {
                        $schoolAdmin = $user->admins()->create([
                            'name' => $user->full_name,
                            'role' => 'SCHOOLADMIN',
                            'level' => 10,
                        ]);

                        $schoolAdmin->schools()->attach($school);

                        $schoolProfile['account_id'] = $school->id;
                        $schoolProfile['account_type'] = $request->create;
                        $schoolProfile['profile'] = 'App\YourEdu\School';
                        $schoolProfile['profile_name'] = $school->company_name;
                        $schoolProfile['profile_url'] = $school->profile->url;
                        DB::commit();
                        return response()->json([
                            'status' => (bool) $school,
                            'school' => $school,
                            'owned_profile' => $schoolProfile,
                        ]);
                    }
                }
    
                DB::rollback();
                return response()->json([
                    'status' => false,
                    'message' => 'unsuccessful',
                ]);
            } catch (\Throwable $th) {
                DB::rollback();
                throw $th;
            }
            
        } else if ($request->create === 'parent') {
            //tested
            $user = User::find(auth()->id());
            $parent = null;
            $parentProfile = [];

            try {
                if ($user) {
                    DB::beginTransaction();
    
                    if (now()->diffInYears($user->dob) < 18  ||  $user->dob->year === $user->created_at->year) {
                        DB::rollback();
                        return response()->json([
                            'status' => false,
                            'message' => $this->ageInappropriateMessage('parent')
                        ]);
                    }

                    if ($user->parent()->exists()) {
                        return response()->json([
                            'status' => false,
                            'message' => 'You are already a parent. You can choose to parent many learners with the same parent account'
                        ]);
                    }

                    if ($request->name ==='') {
                        $inputName =  $user->full_name;
                    }else {
                        $inputName = $request->name;
                    }            
    
                    $parent = $user->parent()->create([
                        'name' => $inputName, 
                    ]);
                } else{
                    return response()->json([
                        'status' => (bool) $user,
                        'message' => 'unsuccessful. user not found.'
                    ]);
                }
    
                $parentProfile['account_id'] = $parent->id;
                $parentProfile['account_type'] = $request->create;
                $parentProfile['profile'] = 'App\YourEdu\ParentModel';
                $parentProfile['profile_name'] = $parent->name;
                $parentProfile['profile_url'] = $parent->profile->url;

                DB::commit();
                return response()->json([
                    'status' => (bool) $parent,
                    'parent' => $parent,
                    'owned_profile' => $parentProfile,
                ]);
            } catch (\Throwable $th) {
                DB::rollback();
                throw $th;
            }
            
        } else if ($request->create === 'group') {
            if ($request->creator === 'learner') {
                
            } else if ($request->creator === 'professional') {
                
            } else if ($request->creator === 'facilitator') {
                
            } else if ($request->creator === 'parent') {
                
            } else if ($request->creator === 'school') {
                
            }
            
        }
    }
}
