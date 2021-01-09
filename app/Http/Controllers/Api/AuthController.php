<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\SecretQuestionResource;
use App\Http\Resources\UserResource;
use App\User;
use App\YourEdu\Admin;
use App\YourEdu\School;
use App\YourEdu\SecretQuestion;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    //
    public function authFailed(Request $request)
    {
        return response()->json([
            'message' => 'unauthenticated'
        ]);
    }

    public function getUser()
    {
        return response()->json([
            'user'=> new UserResource(auth()->user())
        ]);
    }

    public function searchUser(Request $request)
    {
        $users = User::where('username','like',"%$request->search%")
            ->orWhere('first_name',"%$request->search%")
            ->orWhere('other_names',"%$request->search%")
            ->orWhere('last_name',"%$request->search%")
            ->oldest()
            ->paginate(10);

        return UserResource::collection($users);
    }

    public function editUser(Request $request, User $user)
    {
        $input = [];

        if ($user) {

            $input['first_name'] = $user->first_name === $request->first_name ? 
                $user->first_name: $request->first_name;
            $input['last_name'] = $user->last_name === $request->last_name ? 
                $user->last_name: $request->last_name;
            $input['other_names'] = $user->other_names === $request->other_names ? 
                $user->other_names: $request->other_names;
            $input['gender'] = $user->gender === $request->gender || 
                $request->gender != 'MALE' || $request->gender != 'FEMALE'? 
                $user->gender: $request->gender;
            $input['email'] = $user->email === $request->email ? 
                $user->email: $request->email;

            if ($request->has('dob')) {
                $input['dob'] = Carbon::parse($user->dob)->toDateTimeString();
            }
            
            $input['secret_question_id'] = null;
            $input['secret_answer'] = null;
            if ($request->has('question_id') && $request->has('answer')) {
                $question = SecretQuestion::find($request->question_id);

                if ($question) {
                    $input['secret_question_id'] = $user->secret_question_id === $request->question_id ? 
                        $user->secret_question_id: $request->question_id;
                    $input['secret_answer'] = $user->secret_answer === $request->answer ? 
                        $user->secret_answer: $request->answer;
                }
            }

            try {
                DB::beginTransaction();

                $user->update([
                    $input
                ]);

                DB::commit();
                return response()->json([
                    'message' => 'successful',
                    'user' => $user,
                ]);

            } catch (\Throwable $th) {
                DB::rollback();

                // return response()->json([
                //     'message' => 'update unsuccessful'
                // ],422);
                throw $th;
            }
        } else {
            return response()->json([
                'message' => 'user not found'
            ],422);
        }
    }

    public function getSecretQuestions()
    {
        try {
            $secret = SecretQuestion::all();

            return response()->json([
                'message' => 'successful',
                'questions' => SecretQuestionResource::collection($secret),
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'unsuccessful',
            ],422);
            //throw $th;
        }
    }

    public function postSecretQuestions(Request $request)
    {
        if ($request->has('admin_id')) {
            $admin = Admin::find($request->admin_id);

            try {
                if ($admin && ($admin->role === 'SUPERADMIN' || $admin->role === 'SUPERVISOR')) {
                
                    DB::beginTransaction();
                    $input = [];
                    $input['question'] = $request->question;
        
                    if ($admin->role === 'SUPERADMIN') {
                        $input['approved'] = true;
                    } else {
                        $input['approved'] = false;
                    }
                    // return $input;
                    $questionCheck = SecretQuestion::where('question',$request->question)->get();
                    
                    if ($questionCheck->count() > 0) {
                        return response()->json([
                            'message' => 'unsuccessful',
                            'questions' => 'question already exits',
                        ]);
                    }
                    $question = $admin->secretQuestions()->create($input);
    
                    if ( $question) {

                        if ($request->has('possible_answers')) {
                            
                            $input = [];
                            $input = $request->possible_answers;
                            foreach ($input as $value) {
                                $question->possibleAnswers()->create([
                                    'option' => $value
                                ]);
                            }
                        }
    
                        DB::commit();
                        return response()->json([
                            'message' => 'successful',
                            'questions' => $question,
                            'possible_answers' => $question->possibleAnswers,
                        ]);
                        
                    } else {
                        
                        DB::rollback();
                        return response()->json([
                            'message' => 'unsuccessful',
                        ],422);
                    }
                }
            } catch (\Throwable $th) {
                DB::rollback();
                // return response()->json([
                //     'message' => 'unsuccessful',
                // ],422);
                throw $th;
            }
        }

        return response()->json([
            'message' => 'successful',
            'questions' => 'successful',
        ]);
    }

    public function register (Request $request)
    {        
        $input = $request->all();
        $input['dob'] = Carbon::parse($input['dob'])->toDateTimeString();
        $validator = Validator::make($input, [
            'username'=> 'required|alpha_dash|min:8|max:100|unique:users',
            'email' => 'nullable|email|unique:users',
            'password' => 'required|confirmed',
            'password_confirmation'=>'required|string',
            'first_name' => 'nullable|string',
            'last_name' => 'nullable|string',
            'other_names' => 'nullable|string',
            'dob' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 401);
        }

        try {
            $input['password'] = bcrypt($input['password']);
            $user = User::create($input);

            if ($user) {
                
                $user->logins()->create();
                $token = $user->createToken('YourEdu')->accessToken;
                return response()->json([
                    'status' => (bool) $user,
                    'user'=> new UserResource($user),
                    'token' => $token,
                    ]);
            } else {
                return response()->json([
                    'status' => (bool) $user,
                    'error'=>'was unable to create the user',
                ], 401);
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function login (LoginRequest $request)
    {
        try {
            $userQuery = User::query();
            if ($request->has('username')) {
                $userQuery->where('username',$request->username);
            } else if ($request->has('email')) {
                $userQuery->where('email',$request->email);
            }
            
            $user = $userQuery->first();

            if (is_null($user)) {
                return response()->json([
                    'error'=>'oops...not sure you are user. Please click register to register for a user account.'
                ], 401);
            }
            if (!Hash::check($request->password, $user->password)) {
                return response()->json([
                    'error'=>'Please check username/email and/or password combinations.'
                ], 401);
            }

            $user->logins()->create();
            $token = $user->createToken('YourEdu')->accessToken;
            // return $token;
            return response()->json([
                'success'=> (bool) $user,
                'user'=> new UserResource($user),
                'token'=>$token
            ]);

            // return response()->json([
            //     'error'=>'Unauthorised'
            // ], 401);
        } catch (\Throwable $th) {
            throw $th;
        }
            
    }

    public function logout()
    {
        try {
            auth()->logout();
    
            return response()->json([
                'message' => 'successful'
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    

    
}
