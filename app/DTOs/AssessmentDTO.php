<?php

namespace App\DTOs;

use App\DTOs\AssessmentSectionDTO;
use App\DTOs\ModelDTO;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class AssessmentDTO
{
    public string | null $assessmentId;
    public string | null $name;
    public string | null $description;
    public string $state = '';
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
    public ?Model $assessmentable = null;
    public ?Collection $assessmentables= null;
    public array $assessmentSections = [];
    public array $removedAssessmentSections = [];
    public array $editedAssessmentSections = [];
    public ?string $methodType = null;
    public ?string $action = null;
    public object | null $discussionData;
    public array | null $discussionFiles;
    public array | null $paymentData;
    public array | null $removedPaymentData;

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
        $self->publishedAt = $request->publishedAt ?
            Carbon::parse($request->publishedAt) : null;
        $self->dueAt = $request->dueAt ?
            Carbon::parse($request->dueAt) : null;
        $self->restricted = $request->restricted ? 
            json_decode($request->restricted) : false;
        $self->assessmentSections = !is_null(json_decode($request->assessmentSections)) ? 
            AssessmentSectionDTO::createFromArray(
                json_decode($request->assessmentSections), $request
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
                json_decode($request->editedAssessmentSections), $request
            ) : [];
        $self->discussionData = $request->discussionData ?
            json_decode($request->discussionData) : null;
        $self->discussionFiles = $request->hasFile('discussionFiles') ?
            $request->file('discussionFile') : [];
        $self->removedPaymentData = $request->removedPaymentData ?
            json_decode($request->removedPaymentData) : [];
        $self->paymentData = $request->paymentData ?
            json_decode($request->paymentData) : [];

        return $self;
    }
}