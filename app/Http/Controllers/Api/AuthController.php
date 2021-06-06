<?php

namespace App\Http\Controllers\Api;

use App\DTOs\AuthDTO;
use App\DTOs\UserDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateAccountRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Resources\OwnedProfileResource;
use App\Http\Resources\SecretQuestionResource;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
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
        $users = User::query()
            ->whereSearch($request->search)
            ->oldest()
            ->paginate(10);

        return UserResource::collection($users);
    }

    public function editUser(Request $request)
    {
        try {
            DB::beginTransaction();

            $user = (new AuthService)->editUser(
                AuthDTO::createFromRequest($request)
            );

            DB::commit();

            return response()->json([
                'message' => 'successful',
                'user' => $user,
            ]);
        } catch (\Throwable $th) {
            DB::rollback();

            throw $th;
        }
    }

    public function getQuestions(Request $request)
    {
        try {
            $questions = (new AuthService)->getUserQuestions(
                AuthDTO::createFromRequest($request)
            );

            return response()->json([
                'message' => 'successful',
                'questions' => SecretQuestionResource::collection($questions),
            ]);

        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function createAccount(CreateAccountRequest $request)
    {
        try {
            DB::beginTransaction();

            $account = (new AuthService)->createAccount(
                AuthDTO::createFromRequest($request)
            );
            
            DB::commit();

            return response()->json([
                'message' => 'successful',
                'profile' => $account ? 
                    new OwnedProfileResource($account->profile) : null,
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }

    public function updateAccount(Request $request)
    {
        try {
            DB::beginTransaction();

            $account = (new AuthService)->updateAccount(
                AuthDTO::new()
                    ->addData(
                        user: $request->user(),
                        account: $request->account,
                        accountId: $request->accountId,
                    )
                    ->addAccountData($request)
            );
            
            DB::commit();

            return response()->json([
                'message' => 'successful',
                'profile' => $account ? 
                    new OwnedProfileResource($account->profile) : null,
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }

    public function deleteAccount(Request $request)
    {
        try {
            DB::beginTransaction();

            (new AuthService)->deleteAccount(
                AuthDTO::new()
                    ->addData(
                        user: $request->user(),
                        account: $request->account,
                        accountId: $request->accountId,
                    )
            );
            
            DB::commit();

            return response()->json([
                'message' => 'successful',
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }

    public function createUserAnswer(Request $request)
    {
        try {
            DB::beginTransaction();
            
            (new AuthService)->createUserAnswer(
                AuthDTO::createFromRequest($request)
            );

            DB::commit();

            return response()->json([
                'message' => 'successful',
            ]);
        } catch (\Throwable $th) {
            DB::rollback();

            throw $th;
        }
    }

    public function deleteUserAnswer(Request $request)
    {
        try {
            DB::beginTransaction();
            
            (new AuthService)->deleteUserAnswer(
                AuthDTO::new()->addData(
                    answerId: $request->answerId,
                    user: $request->user(),
                )
            );

            DB::commit();

            return response()->json([
                'message' => 'successful',
            ]);
        } catch (\Throwable $th) {
            DB::rollback();

            throw $th;
        }
    }

    public function createUserQuestion(Request $request)
    {
        try {
            DB::beginTransaction();
            
            (new AuthService)->createUserQuestion(
                AuthDTO::createFromRequest($request)
            );

            DB::commit();

            return response()->json([
                'message' => 'successful',
            ]);
        } catch (\Throwable $th) {
            DB::rollback();

            throw $th;
        }
    }

    public function deleteUserQuestion(Request $request)
    {
        try {
            DB::beginTransaction();
            
            (new AuthService)->deleteUserQuestion(
                AuthDTO::new()->addData(
                    questionId: $request->questionId,
                    user: $request->user(),
                )
            );

            DB::commit();

            return response()->json([
                'message' => 'successful',
            ]);
        } catch (\Throwable $th) {
            DB::rollback();

            throw $th;
        }
    }

    public function getUserUsingSecretAnswerPair(Request $request)
    {
        try {
            
            $user = (new AuthService)->getUserUsingSecretAnswerPair(
                AuthDTO::new()->addData(
                    answer: $request->answer,
                    questionId: $request->questionId,
                )
            );

            return response()->json([
                'status' => (bool) $user,
                'user' => $user,
                'token' => $user->token(),
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function register (RegisterUserRequest $request)
    {
        try {
            DB::beginTransaction();

            $user = (new AuthService)->register(
                UserDTO::createFromRequest($request)
            );

            DB::commit();

            return response()->json([
                'status' => (bool) $user,
                'user'=> new UserResource($user),
                'token' => $user->token(),
            ]);
        } catch (\Throwable $th) {
            DB::rollback();

            throw $th;
        }
    }

    public function unregister(Request $request)
    {
        try {
            DB::beginTransaction();

            (new AuthService)->unregister(
                UserDTO::createFromRequest($request)
            );

            DB::commit();

            return response()->json([
                'message' => 'successful',
            ]);
        } catch (\Throwable $th) {
            DB::rollback();

            throw $th;
        }
    }

    public function login (LoginRequest $request)
    {
        try {
            
            $user = (new AuthService)->login(
                UserDTO::createFromRequest($request)
            );

            return response()->json([
                'status'=> (bool) $user,
                'user'=> new UserResource($user),
                'token'=>$user->token()
            ]);

        } catch (\Throwable $th) {
            throw $th;
        }
            
    }

    public function logout(Request $request)
    {
        try {
            
            (new AuthService)->logout(
                AuthDTO::new()->withUser($request->user())
            );

            return response()->json([
                'message' => 'successful'
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    
}
