<?php

namespace App\Http\Controllers\Api;

use App\DTOs\SubjectDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\SubjectAliasCreateRequest;
use App\Http\Requests\SubjectCreateRequest;
use App\Http\Resources\SubjectResource;
use App\Services\SubjectService;
use App\YourEdu\Subject;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubjectController extends Controller
{
    public function createSubjectAsAttachment(SubjectCreateRequest $request)
    {
        try {
            DB::beginTransaction();

            $subject = (new SubjectService())->createSubjectAsAttachment(
                SubjectDTO::createFromRequest($request)
            );

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

    public function createSubjectAttachmentAlias(SubjectAliasCreateRequest $request)
    {
        try {
            DB::beginTransaction();

            $mainSubject = (new SubjectService())->createSubjectAttachmentAlias(
                SubjectDTO::createFromRequest($request)
            );

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
        return SubjectResource::collection(Subject::paginate(2));
    }

    public function subjectsSearch($search)
    {
        $subjects = Subject::where('name', 'like', "%{$search}%")
            ->orWhereHas('aliases', function (Builder $query) use ($search) {
                $query->where('name', 'like', "%{$search}%");
            })->get();

        return response()->json([
            'message' => 'successful',
            'subjects' => SubjectResource::collection($subjects)
        ]);
    }

    public function deleteSubjectAsAttachment(Request $request)
    {
        try {
            (new SubjectService())->deleteSubjectAsAttachment(
                SubjectDTO::new()
                    ->addData(
                        subjectId: $request->subjectId,
                        userId: $request->user()?->id,
                    )
            );

            return response()->json([
                'message' => 'successful'
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
