<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AcademicYearResource;
use App\Http\Resources\AcademicYearSectionResource;
use App\Http\Resources\DashboardAccountResource;
use App\Http\Resources\DashboardAdminResource;
use App\Http\Resources\DashboardItemResource;
use App\Http\Resources\DashboardUserResource;
use App\Http\Resources\UserAccountResource;
use App\Http\Resources\UserResource;
use App\Services\DashboardService;
use App\Services\SchoolService;
use Illuminate\Http\Request;
use \Debugbar;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function getUsers(Request $request)
    {
        try {
            $users = (new DashboardService())->getUsersOrAdmins($request->account,
                $request->accountId,auth()->id(),'users');

            return DashboardUserResource::collection($users);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function getAdmins(Request $request)
    {
        try {
            $admin = (new DashboardService())->getUsersOrAdmins($request->account,
                $request->accountId,auth()->id(),'admins');

            return DashboardAdminResource::collection($admin);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function banninguser(Request $request)
    {
        try {
            $banArray = (new DashboardService())->banningAccount(
                $request->action,
                $request->account,
                $request->accountId,
                $request->adminId,
                auth()->id(),
                $request->banId,
                $request->state,
                $request->type,
            );

            return response()->json([
                'message' => 'successful',
                'account' => $request->account === 'user' ? 
                    new UserResource($banArray['account']) :
                    new UserAccountResource($banArray['account']),
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function getAccountDetails(Request $request)
    {
        try {
            $account = (new DashboardService())->getAccountDetails($request->account,
                $request->accountId,auth()->id(),$request->owner);

            return response()->json([
                'message' => 'successful',
                'accountDetails' => new DashboardAccountResource($account),
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function getSectionItemData(Request $request)
    {
        try {
            $item = (new DashboardService())->getSectionItemData($request->item,$request->itemId);

            return response()->json([
                'message' => 'successful',
                'mainSectionData' => new DashboardItemResource($item),
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function createAcademicYear(Request $request)
    {
        try {
            DB::beginTransaction();
            $academicYear = (new SchoolService())->createAcademicYear($request->schoolId,
                $request->name,$request->startDate,$request->endDate,
                $request->description,json_decode($request->sections),auth()->id(),
                $request->account,$request->accountId);
                
            DB::commit();
            return response()->json([
                'message' => 'successful',
                'academicYear' => new AcademicYearResource($academicYear)
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }

    public function createAcademicYearSection(Request $request)
    {
        try {
            DB::beginTransaction();
            $academicYearSection = (new SchoolService())->createAcademicYearSection(
                $request->schoolId,$request->academicYearId,
                $request->name,$request->promotion,$request->startDate,$request->endDate,
                auth()->id());
                
            // DB::commit();
            return response()->json([
                'message' => 'successful',
                'academicYearSection' => new AcademicYearSectionResource($academicYearSection)
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }
}
