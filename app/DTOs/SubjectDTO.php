<?php

namespace App\DTOs;

use App\Traits\AliasDTOTrait;
use App\Traits\DTOTrait;
use App\YourEdu\Subject;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class SubjectDTO
{
    use DTOTrait,
        AliasDTOTrait;

    public ?string $subjectId = null;
    public ?Model $addedby = null;
    public ?Subject $subject = null;
    public ?string $account = null;
    public ?string $accountId = null;
    public ?string $name = null;
    public ?string $description = null;
    public ?string $rationale = null;
    public array $aliases = [];

    public static function createFromRequest(Request $request)
    {
        $self = new static;

        $self->subjectId = $request->subjectId;
        $self->account = $request->account;
        $self->accountId = $request->accountId;
        $self->name = $request->name;
        $self->description = $request->description;
        $self->rationale = $request->rationale;
        $self->userId = $request->user()?->id;
        $self->aliases = $request->aliases ?
            json_decode($request->aliases) : [];

        return $self;
    }
}
