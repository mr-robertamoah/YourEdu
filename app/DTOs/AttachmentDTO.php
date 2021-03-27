<?php

namespace App\DTOs;

use Illuminate\Database\Eloquent\Model;

class AttachmentDTO
{
    public string | null $account;
    public string | null $accountId;
    public string | null $name;
    public string | null $description;
    public string | null $rationale;
    public array $aliases = [];
    public string | null $type;
    public string | null $typeId;
    public ?Model $addedby = null;

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
