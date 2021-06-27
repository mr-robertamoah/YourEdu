<?php

namespace App\DTOs;

use App\Contracts\ItemDataContract;
use App\Traits\AliasDTOTrait;
use App\Traits\DTOTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class CourseDTO extends ItemDataContract
{
    use DTOTrait,
        AliasDTOTrait;

    public ?string $courseId = null;
    public ?string $name = null;
    public bool $facilitate = false;
    public bool $isAttachment = false;
    public bool $standAlone = false;
    public array $sections = [];
    public array $removedSections = [];
    public array $editedSections = [];
    public array $items = [];
    public array $removedItems = [];
    public array $attachments = [];
    public array $removedAttachments = [];
    public ?object $discussionData = null;
    public array $discussionFiles = [];
    public ?string $action = null;
    public ?string $owner = null;
    public ?string $ownerId = null;
    public ?string $adminId = null;
    public ?string $account = null;
    public ?string $accountId = null;
    public ?string $description = null;
    public ?string $state = null;
    public ?string $methodType = null;
    public ?string $method = null;
    public ?PaymentDTO $paymentDTO = null;
    public ?PaymentDTO $removedPaymentDTO = null;
    public ?Model $addedby = null;
    public ?Model $ownedby = null;

    public static function createFromRequest(Request $request)
    {
        $self = new static();

        $self->courseId = $request->courseId;
        $self->adminId = $request->adminId;
        $self->action = $request->action;
        $self->name = $request->name;
        $self->owner = $request->owner;
        $self->ownerId = $request->ownerId;
        $self->account = $request->account;
        $self->accountId = $request->accountId;
        $self->userId = (int) $request->user()->id;
        $self->description = $request->description;
        $self->state = $request->state;
        $self->facilitate = json_decode($request->facilitate);
        $self->standAlone = $request->standAlone ?
            json_decode($request->standAlone) : false;
        $self->items = $request->items ?
            json_decode($request->items) : [];
        $self->removedItems = $request->removedItems ?
            json_decode($request->removedItems) : [];
        $self->attachments = $request->attachments ?
            json_decode($request->attachments) : [];
        $self->removedAttachments = $request->removedAttachments ?
            json_decode($request->removedAttachments) : [];
        $self->removedPaymentDTO = static::createPaymentDTOForRemovedPayments(
            $request->removedPaymentData
        );
        $self->paymentDTO = static::createPaymentDTOForPayments(
            $request->paymentType,
            $request->paymentData
        );
        $self->sections = $request->sections ?
            json_decode($request->sections) : [];
        $self->removedSections = $request->removedSections ?
            json_decode($request->removedSections) : [];
        $self->editedSections = $request->editedSections ?
            json_decode($request->editedSections) : [];
        $self->discussionData = $request->discussionData ?
            json_decode($request->discussionData) : null;
        $self->discussionFiles = $request->hasFile('discussionFiles') ?
            $request->file('discussionFile') : [];

        return $self;
    }

    public function withAddedby(Model $addedby)
    {
        $clone = clone $this;

        $clone->addedby = $addedby;

        return $clone;
    }

    public function withOwnedby(Model $ownedby)
    {
        $clone = clone $this;

        $clone->ownedby = $ownedby;

        return $clone;
    }
}
