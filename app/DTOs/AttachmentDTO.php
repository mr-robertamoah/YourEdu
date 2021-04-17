<?php

namespace App\DTOs;

use Illuminate\Database\Eloquent\Model;

class AttachmentDTO
{
    public ?string $account = null;
    public ?string $accountId = null;
    public ?string $name = null;
    public ?string $description = null;
    public ?string $rationale = null;
    public array $aliases = [];
    public ?string $type = null;
    public ?string $typeId = null;
    public ?Model $addedby = null;
    public ?string $method = null;

    public static function createFromData
    (
        $name = null,
        $account = null,
        $accountId = null,
        $description = null,
        $rationale = null,
        $aliases = [],
        $type = null,
        $typeId = null,
    )
    {
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

    public function withAddedby(Model $addedby)
    {
        $clone = clone $this;

        $clone->addedby = $addedby;

        return $clone;
    }
}
