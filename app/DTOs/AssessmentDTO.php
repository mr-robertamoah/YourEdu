<?php

namespace App\DTOs;

use App\DTOs\AssessmentSectionDTO;
use App\DTOs\ModelDTO;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class AssessmentDTO
{
    public string | null $assessmentId;
    public string | null $name;
    public string | null $description;
    public Carbon | null $publishedAt;
    public Carbon | null $dueAt;
    public int | null $userId;
    public int | null $duration;
    public int | null $totalMark;
    public string | null $type;
    public bool $restricted = false;
    public string | null $account;
    public string | null $accountId;
    public array $attachedItems = [];
    public array $unattachedItems = [];
    public string | null $adminId;
    public ?Model $addedby = null;
    public Model | null $assessmentable;
    public array $assessmentSections = [];
    public array $removedAssessmentSections = [];
    public array $editedAssessmentSections = [];

    public static function createFromRequest(Request $request)
    {
        $self = new static();

        $self->assessmentId = $request->assessmentId;
        $self->adminId = $request->adminId;
        $self->name = $request->name;
        $self->type = $request->type && strlen($request->type) ?
            strtoupper($request->type) : "PUBLIC";
        $self->account = $request->account;
        $self->accountId = $request->accountId;
        $self->userId = (int) $request->user()->id;
        $self->totalMark = (int) $request->totalMark;
        $self->duration = (int) $request->duration;
        $self->description = $request->description;
        $self->publishedAt = Carbon::parse($request->publishedAt);
        $self->dueAt = Carbon::parse($request->dueAt);
        $self->restricted = $request->restricted ? 
            json_decode($request->restricted) : false;
        $self->assessmentSections = !is_null(json_decode($request->assessmentSections)) ? 
            AssessmentSectionDTO::createFromArray(
                json_decode($request->assessmentSections)
            ) : [];
        $self->attachedItems = !is_null($request->attachedItems) ? 
            ModelDTO::createFromArray(
                json_decode($request->attachedItems)
            ) : [];
        $self->unattachedItems = !is_null($request->unattachedItems) ? 
            ModelDTO::createFromArray(
                json_decode($request->unattachedItems)
            ) : [];
        $self->removedAssessmentSections = !is_null($request->removedAssessmentSections) ? 
            AssessmentSectionDTO::createFromArray(
                json_decode($request->removedAssessmentSections)
            ) : [];
        $self->editedAssessmentSections = !is_null($request->editedAssessmentSections) ? 
            AssessmentSectionDTO::createFromArray(
                json_decode($request->editedAssessmentSections)
            ) : [];

        return $self;
    }
}