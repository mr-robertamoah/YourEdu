<?php

namespace App\DTOs;

use App\Services\AssessmentSectionData;
use App\Services\ModelData;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class AssessmentData
{
    public string | null $assessmentId;
    public string | null $name;
    public string | null $description;
    public Carbon | null $publishedDate;
    public Carbon | null $dueDate;
    public int | null $userId;
    public int | null $duration;
    public int | null $totalMark;
    public string | null $account;
    public string | null $accountId;
    public array $questionables;
    public array $removedQuestionables;
    public string | null $adminId;
    public Model | null $addedby;
    public Model | null $assessmentable;
    public array $sections;
    public array $removedSections;
    public array $editedSections;

    public static function createFromRequest(Request $request)
    {
        $self = new static();

        $self->$assessmentId = $request->$assessmentId;
        $self->adminId = $request->adminId;
        $self->name = $request->name;
        $self->type = strtoupper($request->type);
        $self->account = $request->account;
        $self->accountId = $request->accountId;
        $self->for = $request->for;
        $self->forId = $request->fortId;
        $self->userId = (int) $request->user()->id;
        $self->totalMark = (int) $request->totalMark;
        $self->duration = (int) $request->duration;
        $self->description = $request->description;
        $self->publishedDate = Carbon::parse($request->publishedDate);
        $self->dueDate = Carbon::parse($request->dueDate);
        $self->sections = !is_null(json_decode($request->sections)) ? 
            AssessmentSectionData::createFromArray(
                json_decode($request->sections)
            ) : [];
        $self->questionables = !is_null(json_decode($request->questionables)) ? 
            ModelData::createFromArray(
                json_decode($request->questionables)
            ) : [];
        $self->removedQuestionables = !is_null(json_decode($request->removedQuestionables)) ? 
            ModelData::createFromArray(
                json_decode($request->removedQuestionables)
            ) : [];
        $self->removedSections = !is_null(json_decode($request->removedSections)) ? 
            AssessmentSectionData::createFromArray(
                json_decode($request->removedSections)
            ) : [];
        $self->editedSections = !is_null(json_decode($request->editedSections)) ? 
            AssessmentSectionData::createFromArray(
                json_decode($request->editedSections)
            ) : [];

        return $self;
    }
}