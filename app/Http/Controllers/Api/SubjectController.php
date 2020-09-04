<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SubjectResource;
use App\YourEdu\Admin;
use App\YourEdu\Facilitator;
use App\YourEdu\Learner;
use App\YourEdu\ParentModel;
use App\YourEdu\Professional;
use App\YourEdu\School;
use App\YourEdu\Subject;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SubjectController extends Controller
{
    //

    public function subjectCreate(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'rationale' => 'nullable|string',
            'aliases' => 'nullable|array',
        ]);
        
        $account = null;

        if ($request->account === 'facilitator') {
            $account = Facilitator::find($request->accountId);
        } else if ($request->account === 'professional') {
            $account = Professional::find($request->accountId);
        } else if ($request->account === 'admin') {
            $account = Admin::find($request->accountId);
        } else if ($request->account === 'school') {
            $account = School::find($request->accountId);
        } else {
            return response()->json([
                'message' => "unsuccessful, {$request->account} is not a valid account to create a subject"
            ],422);
        } 

        if (is_null($account)) {
            return response()->json([
                'message' => "unsuccessful, {$request->account} does not exist"
            ],422);
        }

        try { 

            DB::beginTransaction();
            $subject = $account->uniqueSubjectsAdded()->create([
                'name' => $request->name,
                'description' => $request->description,
                'rationale' => $request->rationale,
            ]);

            if (!$subject) {
                return response()->json([
                    'message' => 'unsuccessful, subject was not created'
                ],422);
            }

            if ($request->has('aliases')) {
                
                foreach ($request->aliases as $key => $aliasName) {
                    if (Str::length($aliasName)) {

                        $alias = $account->aliasesAdded()->create([
                            'name' => $aliasName,
                        ]);

                        $alias->aliasable()->associate($subject);
                        $alias->save();
                    }
                }

            }

            $account->point->value += 1;
            $account->point->save();

            DB::commit();
            return response()->json([
                'message' => "successful",
                'subject' => new SubjectResource($subject)
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
            // return response()->json([
            //     'message' => "unsuccessful, something happened."
            // ],422);
        }

    }

    public function subjectAliasCreate(Request $request,$subject)
    {
        $mainSubject = Subject::find($subject);

        if (!$mainSubject) {
            return response()->json([
                'message' => 'unsuccessful, subject does not exist'
            ],422);
        }

        $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
        ]);
        
        $account = null;

        if ($request->account === 'learner') { //learner and parents can only create aliases
            $account = Learner::find($request->accountId);
        } else if ($request->account === 'parent') {
            $account = ParentModel::find($request->accountId);
        } else if ($request->account === 'facilitator') {
            $account = Facilitator::find($request->accountId);
        } else if ($request->account === 'professional') {
            $account = Professional::find($request->accountId);
        } else if ($request->account === 'admin') {
            $account = Admin::find($request->accountId);
        } else if ($request->account === 'school') {
            $account = School::find($request->accountId);
        } else {
            return response()->json([
                'message' => "unsuccessful, {$request->account} is not a valid account to create an alias for a subject"
            ],422);
        } 

        if (is_null($account)) {
            return response()->json([
                'message' => "unsuccessful, {$request->account} does not exist"
            ],422);
        }

        try {

            $aliasCheck = $mainSubject->aliases()->where('name',$request->name)->count();
            if ($aliasCheck) {
                return response()->json([
                    'message' => 'unsuccessful, subject already has this alias'
                ],422);
            }

            DB::beginTransaction();
            $alias = $account->aliasesAdded()->create([
                'name' => $request->name,
                'description' => $request->description,
            ]);

            $alias->aliasable()->associate($mainSubject);
            $alias->save();

            if (!$alias) {
                return response()->json([
                    'message' => 'unsuccessful, alias was not created'
                ],422);
            }

            $account->point->value += 1;
            $account->point->save();

            DB::commit();
            return response()->json([
                'message' => "successful",
                'subject' => new SubjectResource($mainSubject)
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
            // return response()->json([
            //     'message' => "unsuccessful, something happened."
            // ],422);
        }

    }

    public function subjectsGet()
    {
        return [
            'message' => 'successful',
            'subjects' => SubjectResource::collection(Subject::all())
        ];
    }

    public function subjectsSearch($search)
    {
        $subjects = Subject::where('name','like',"%{$search}%")
            ->orWhereHas('aliases',function(Builder $query) use ($search){
                $query->where('name','like',"%{$search}%");
            })->get();

        return response()->json([
            'message' => 'successful',
            'subjects' => SubjectResource::collection($subjects)
        ]);
    }

    public function subjectsDelete($subject)
    {
        $mainSubject = Subject::find($subject);
        if (!$mainSubject) {
            return response()->json([
                'message' => 'unsuccessful, subject does not exist'
            ],422);
        }

        if ($mainSubject->addedby->user_id !== auth()->id()) {
            return response()->json([
                'message' => 'unsuccessful, you cannot delete subject you did not create'
            ],422);
        }

        $mainSubject->delete();

        return response()->json([
            'message' => 'successful'
        ]);
    }
}
