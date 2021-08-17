<?php

namespace App\DTOs;

use App\DTOs\AssessmentSectionDTO;
use App\DTOs\ModelDTO;
use App\Traits\DTOTrait;
use App\YourEdu\Assessment;
use App\YourEdu\Participant;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class AssessmentDTO
{
    use DTOTrait;

    public ?string $assessmentId = null;
    public ?string $participantId = null;
    public ?string $name = null;
    public ?string $description = null;
    public ?string $state = '';
    public ?Carbon $publishedAt = null;
    public ?Carbon $dueAt = null;
    public bool $social = false;
    public ?int $duration = null;
    public ?int $totalMark = null;
    public ?string $type = null;
    public ?string $account = null;
    public ?string $accountId = null;
    public array $attachedItems = [];
    public array $unattachedItems = [];
    public ?string $adminId = null;
    public ?Model $addedby = null;
    public ?Model $assessmentable = null;
    public ?Collection $assessmentables = null;
    public array $assessmentSections = [];
    public array $removedAssessmentSections = [];
    public array $editedAssessmentSections = [];
    public array $attachments = [];
    public array $removedAttachments = [];
    public ?string $method = null;
    public ?string $methodType = null;
    public ?string $action = null;
    public ?object $discussionData = null;
    public ?array $discussionFiles = null;
    public ?array $paymentData = null;
    public ?array $removedPaymentData = null;
    public ?Assessment $assessment = null;
    public ?Participant $participant = null;
    public ?InvitationDTO $invitationDTO = null;
    public ?Collection $users = null;

    public static function createFromRequest(Request $request)
    {
        $self = new static();

        $self->assessmentId = $request->assessmentId;
        $self->adminId = $request->adminId;
        $self->social = $request->social ? json_decode($request->social) : false;
        $self->name = $request->name;
        $self->type = $request->type && strlen($request->type) ?
            strtoupper($request->type) : "PUBLIC";
        $self->account = $request->account;
        $self->accountId = $request->accountId;
        $self->action = $request->action;
        $self->participantId = $request->participantId;
        $self->userId = $request->user()->id;
        $self->totalMark = (int) $request->totalMark ?: 100;
        $self->duration = (int) $request->duration;
        $self->description = $request->description;
        $self->publishedAt = $request->publishedAt ?
            Carbon::parse($request->publishedAt) : null;
        $self->dueAt = $request->dueAt ?
            Carbon::parse($request->dueAt) : null;
        $self->assessmentSections = !is_null(json_decode($request->assessmentSections)) ?
            AssessmentSectionDTO::createFromArray(
                json_decode($request->assessmentSections),
                $request
            ) : [];
        $self->attachments = $request->attachments ?
            ModelDTO::createFromArray(
                json_decode($request->attachments)
            ) : [];
        $self->removedAttachments = $request->removedAttachments ?
            ModelDTO::createFromArray(
                json_decode($request->removedAttachments)
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
                json_decode($request->editedAssessmentSections),
                $request
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

    public function getAssessmentSectionDurations()
    {
        $durations = array_sum(
            array_map(function ($assessmentSectionDTO) {
                return (int) $assessmentSectionDTO->duration;
            }, $this->assessmentSections)
        );

        $durations += array_sum(
            array_map(function ($assessmentSectionDTO) {
                return $assessmentSectionDTO->duration ?
                    (int) $assessmentSectionDTO->duration :
                    getYourEduModel('assessmentSection', $assessmentSectionDTO->id)->duration;
            }, $this->editedAssessmentSections)
        );

        $durations -= array_sum(
            array_map(function ($assessmentSectionDTO) {
                return getYourEduModel('assessmentSection', $assessmentSectionDTO->id)->duration;
            }, $this->deletedAssessmentSections)
        );
    }
}
