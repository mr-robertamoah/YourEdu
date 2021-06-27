<?php

namespace App\DTOs;

use App\Traits\DTOTrait;
use App\YourEdu\PostAttachment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class AttachmentDTO
{
    use DTOTrait;

    public ?string $account = null;
    public ?string $accountId = null;
    public ?string $attachmentId = null;
    public ?string $item = null;
    public ?string $itemId = null;
    public ?string $name = null;
    public ?string $description = null;
    public ?string $note = null;
    public ?string $rationale = null;
    public ?string $ageGroup = null;
    public array $aliases = [];
    public ?string $type = null;
    public ?string $typeId = null;
    public ?Model $addedby = null;
    public ?Model $attachable = null;
    public ?Model $attachedwith = null;
    public ?PostAttachment $attachment = null;
    public ?string $method = null;

    public static function createFromData(
        $name = null,
        $account = null,
        $accountId = null,
        $description = null,
        $rationale = null,
        $aliases = [],
        $type = null,
        $typeId = null,
    ) {
        $self = new static;

        $self->account = $account;
        $self->accountId = $accountId;
        $self->name = $name;
        $self->description = $description;
        $self->rationale = $rationale;
        $self->aliases = is_array($aliases) ? $aliases : [];
        $self->type = $type;
        $self->typeId = $typeId;

        return $self;
    }


    public static function createFromRequest(Request $request)
    {
        $self = new static;

        $self->account = $request->account;
        $self->accountId = $request->accountId;
        $self->item = $request->item;
        $self->itemId = $request->itemId;
        $self->userId = $request->user()?->id;
        $self->name = $request->name;
        $self->note = $request->note;
        $self->rationale = $request->rationale;
        $self->aliases = self::getAliases($request);
        $self->type = $request->type;
        $self->typeId = $request->typeId;

        return $self;
    }

    public static function getAliases($request)
    {
        if (is_null($request->aliases)) {
            return [];
        }

        if (is_string($request->aliases)) {
            return json_decode($request->aliases);
        }

        return $request->aliases;
    }
}
