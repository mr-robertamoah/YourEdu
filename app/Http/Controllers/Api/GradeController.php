<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\GradeResource;
use App\YourEdu\Admin;
use App\YourEdu\Facilitator;
use App\YourEdu\Grade;
use App\YourEdu\Learner;
use App\YourEdu\ParentModel;
use App\YourEdu\Professional;
use App\YourEdu\School;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class GradeController extends Controller
{
    //

    public function gradeCreate(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'ageGroup' => 'nullable|string',
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
            $grade = $account->uniqueGradesAdded()->create([
                'name' => $request->name,
                'description' => $request->description,
                'age_group' => $request->ageGroup,
            ]);

            if (!$grade) {
                return response()->json([
                    'message' => 'unsuccessful, grade was not created'
                ],422);
            }

            if ($request->has('aliases')) {
                
                foreach ($request->aliases as $key => $aliasName) {
                    if (Str::length($aliasName)) {
                        
                        $alias = $account->aliasesAdded()->create([
                            'name' => $aliasName,
                        ]);

                        $alias->aliasable()->associate($grade);
                        $alias->save();
                    }
                }

            }

            $account->point->value += 1;
            $account->point->save();

            DB::commit();
            return response()->json([
                'message' => "successful",
                'grade' => new GradeResource($grade)
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
            // return response()->json([
            //     'message' => "unsuccessful, something happened."
            // ],422);
        }

    }

    public function gradeAliasCreate(Request $request,$grade)
    {
        $mainGrade = Grade::find($grade);

        if (!$mainGrade) {
            return response()->json([
                'message' => 'unsuccessful, grade does not exist'
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
                'message' => "unsuccessful, {$request->account} is not a valid account to create an alias for a grade"
            ],422);
        } 

        if (is_null($account)) {
            return response()->json([
                'message' => "unsuccessful, {$request->account} does not exist"
            ],422);
        }

        try {

            $aliasCheck = $mainGrade->aliases()->where('name',$request->name)->count();
            if ($aliasCheck) {
                return response()->json([
                    'message' => 'unsuccessful, grade already has this alias'
                ],422);
            }

            DB::beginTransaction();
            $alias = $account->aliasesAdded()->create([
                'name' => $request->name,
                'description' => $request->description,
            ]);

            $alias->aliasable()->associate($mainGrade);
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
                'grade' => new GradeResource($mainGrade)
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
            // return response()->json([
            //     'message' => "unsuccessful, something happened."
            // ],422);
        }

    }

    public function gradesGet()
    {
        return [
            'message' => 'successful',
            'grades' => GradeResource::collection(Grade::all())
        ];
    }

    public function gradesSearch($search)
    {
        $subjects = Grade::where('name','like',"%{$search}%")
            ->orWhereHas('aliases',function(Builder $query) use ($search){
                $query->where('name','like',"%{$search}%");
            })->get(); 

        return response()->json([
            'message' => 'successful',
            'grades' => GradeResource::collection($subjects)
        ]);
    }

    public function gradeDelete($grade)
    {
        $mainGrade = Grade::find($grade);
        if (!$mainGrade) {
            return response()->json([
                'message' => 'unsuccessful, grade does not exist'
            ],422);
        }

        if ($mainGrade->addedby->user_id !== auth()->id()) {
            return response()->json([
                'message' => 'unsuccessful, you cannot delete grade you did not create'
            ],422);
        }

        $mainGrade->delete();

        return response()->json([
            'message' => 'successful'
        ]);
    }
}
