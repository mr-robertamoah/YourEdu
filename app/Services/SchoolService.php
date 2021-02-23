<?php

namespace App\Services;

use App\Events\NewAcademicYearEvent;
use App\Events\NewAcademicYearSectionEvent;
use App\Exceptions\AccountNotFoundException;
use App\Exceptions\SchoolException;
use App\Http\Resources\AcademicYearResource;
use App\Http\Resources\AcademicYearSectionResource;
use Carbon\Carbon;
use \Debugbar;

class SchoolService  
{
    public function createAcademicYear($schoolId, $name, $startDate, 
        $endDate, $description, $sections, $userId, $account, $accountId)
    {
        $school = getYourEduModel('school',$schoolId);
        if (is_null($school)) {
            throw new AccountNotFoundException("school not found with id $schoolId");
        }

        if (!$this->validateAcademicYearDates('academicYears',$school,
            Carbon::parse($startDate),Carbon::parse($endDate))) {
            throw new SchoolException(
                "Academic year dates cannot fall within an existing academic year");
        }

        $academicYear = $school->academicYears()->create([
            'name' => $name,
            'description' => $description,
            'start_date' => Carbon::parse($startDate),
            'end_date' => Carbon::parse($endDate),
            'state' => 'ACCEPTED'
        ]);

        if ($school->owner_id === $userId) {
            $academicYear->addedby()->associate(getYourEduModel('user',$userId));
        } else {
            $addedby = getYourEduModel($account,$accountId);
            if (is_null($addedby)) {
                throw new SchoolException("account($account) trying to add an academic year, was not found with id $accountId");
            } else {
                $academicYear->addedby()->associate($addedby);
            }
        }        
        $academicYear->save();

        if (count($sections)) {
            foreach ($sections as $section) {
                $this->createAnAcademicYearSection($academicYear,$school,
                    $section->name,$section->promotion,Carbon::parse($section->startDate),
                    Carbon::parse($section->endDate));
            }
        }

        broadcast(new NewAcademicYearEvent($schoolId,
            new AcademicYearResource($academicYear)))->toOthers();

        return $academicYear->load('academicYearSections');
    }

    public function createAcademicYearSection($schoolId,$academicYearId,$name,$promotion,
        $startDate,$endDate,$userId)
    {
        $school = getYourEduModel('school',$schoolId);
        if (is_null($school)) {
            throw new AccountNotFoundException("school not found with id $schoolId");
        }

        $academicYear = getYourEduModel('academicYear',$academicYearId);
        if (is_null($academicYear)) {
            throw new AccountNotFoundException("academic year not found with id $academicYearId");
        }

        if (!in_array($userId,$school->getAdminIds())) {
            throw new SchoolException("you are not authorized to add an academic year section.");
        }

        $academicYearSection = $this->createAnAcademicYearSection($academicYear,$school->id,
            $name,$promotion,$startDate,$endDate);

        broadcast(new NewAcademicYearSectionEvent($schoolId,
            new AcademicYearSectionResource($academicYearSection)))->toOthers();

        return $academicYearSection;
    }

    private function createAnAcademicYearSection($academicYear,$school,$name,$promotion,
        $startDate,$endDate)
    {
        if (!$this->validateAcademicYearDates('academicYearSections',$school,
            $startDate,$endDate)) {
            throw new SchoolException("$name dates cannot fall within an existing academic year section.");
        }

        $academicYearSection = $academicYear->academicYearSections()->create([
            'school_id' => $school->id,
            'name' => $name,
            'promotion' => $promotion,
            'start_date' =>$startDate,
            'end_date' =>$endDate,
        ]);

        return $academicYearSection;
    }

    /**
     * check whether or not the new academic year being created falls within an existing one
     *
     * @return bool
     */
    private function validateAcademicYearDates($type, $school,$startDate,$endDate)
    {
        $count = $school->$type()
            ->where(function($query) use ($startDate,$endDate){
                $query
                    ->where(function($query) use ($startDate){
                        $query
                            ->whereDate('start_date','<=',$startDate)
                            ->whereDate('end_date','>=',$startDate);
                    })
                    ->orWhere(function($query) use ($endDate){
                        $query
                            ->whereDate('start_date','<=',$endDate)
                            ->whereDate('end_date','>=',$endDate);
                    });
            })
            ->count();

        if ($count > 0) {
            return false;
        }

        // Debugbar::info($startDate);
        // Debugbar::info($endDate);
        // Debugbar::info($count);

        return true;
    }
}
