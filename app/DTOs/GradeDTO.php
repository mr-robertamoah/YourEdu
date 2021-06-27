<?php

namespace App\DTOs;

use App\Traits\AliasDTOTrait;
use App\Traits\DTOTrait;
use App\YourEdu\Grade;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class GradeDTO
{
    use DTOTrait,
        AliasDTOTrait;

    public ?string $gradeId = null;
    public ?Model $addedby = null;
    public ?Grade $grade = null;
    public ?string $account = null;
    public ?string $accountId = null;
    public ?string $name = null;
    public ?string $description = null;
    public ?string $ageGroup = null;
    public ?string $level = null;
    public array $aliases = [];

    public static function createFromRequest(Request $request)
    {
        $self = new static;

        $self->gradeId = $request->gradeId;
        $self->account = $request->account;
        $self->accountId = $request->accountId;
        $self->name = $request->name;
        $self->description = $request->description;
        $self->level = $request->level;
        $self->ageGroup = $request->ageGroup;
        $self->userId = $request->user()?->id;
        $self->aliases = $request->aliases ?
            json_decode($request->aliases) : [];

        return $self;
    }
}
