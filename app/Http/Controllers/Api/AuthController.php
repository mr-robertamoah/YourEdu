<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SecretQuestionResource;
use App\User;
use App\YourEdu\Admin;
use App\YourEdu\School;
use App\YourEdu\SecretQuestion;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    //

    public function getUser(Request $request)
    {
        return response()->json([
            'user'=> $request->user()
        ]);
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
                    
            // return $input;

            try {
                DB::beginTransaction();

                // $user->first_name = $input['first_name'];
                // $user->last_name = $input['last_name'];
                // $user->other_names = $input['other_names'];
                // $user->email = $input['email'];
                // $user->gender = $input['gender'];
                // $user->secret_question_id = $input['secret_question_id'];
                // $user->secret_answer = $input['secret_answer'];
                // $user->save();
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

    public function test(Request $request)
    {
        // return response()->json(School::find(3));
        $filePaths = [];
        
        return response()->json([
            'files' => $request->hasFile('files'),
            'vidoes' => $request->hasFile('videos'),
            'audio' => $request->hasFile('audio'),
            'images' => $request->hasFile('images'),
        ]);

        if ($request->hasFile('files')) {
            $request->validate(
                [
                    'files.*' => 'file|mimes:pdf,pdt,txt,doc,docx,xls,xlsx,'
                ]
            );
            $filePaths['files'] = uploadYourEduFiles($request->file('files'));

            return $request;
        }
        
        if ($request->hasFile('images')) {
            $request->validate(
                [
                    'images.*' => 'image|mimes:jpg,jpeg,bmp,png,gif,webp'
                ]
            );
            $filePaths['images'] = uploadYourEduFiles($request->file('images'));
        }
        
        if ($request->hasFile('audio')) {
            $request->validate(
                [
                    'audio.*' => 'file|mimes:mp3'
                ]
            );
            $filePaths['audio'] = uploadYourEduFiles($request->file('audio'));
        }
        
        if ($request->hasFile('videos')) {
            $request->validate(
                [
                    'vidoes.*' => 'file|mimes:mp4'
                ]
            );
            $filePaths['videos'] = uploadYourEduFiles($request->file('videos'));
        }

        return response()->json(
            [
                'id'=> 1,
                'filePath' => $filePaths
            ]
            );
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
                    'user'=>$user,
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

    public function login (Request $request)
    {
        if ($request->has('username')) {
            $validator = Validator::make($request->all(), [
                'username'=> 'required|alpha_dash|min:8|max:100',
                'password' => 'required',
            ]);
        } else {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required',
            ]);
        }

        if ($validator->fails()) {
            return response()->json(['errors'=> $validator->errors()]);
        }
        
        try {
            if (Auth::attempt(['username'=>$request->username, 'password'=>$request->password]) ||
                Auth::attempt(['email'=>$request->email, 'password'=>$request->password])) {
                
                    // return response()->json(['user'=> Auth::user()]);
                $user = Auth::user();
                $user->logins()->create();
                $token = $user->createToken('YourEdu')->accessToken;
                // return $token;
                return response()->json([
                    'success'=> (bool) $user,
                    'user'=> $user,
                    'token'=>$token
                ]);
            }

            return response()->json([
                'error'=>'Unauthorised'
            ], 401);
        } catch (\Throwable $th) {
            throw $th;
        }
            
    }

    
}
