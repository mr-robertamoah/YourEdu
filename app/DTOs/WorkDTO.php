<?php

namespace App\DTOs;

use Illuminate\Http\Request;

class WorkDTO
{
    public ?string $userId = null;
    public ?string $methodType = null;
    public ?string $assessmentId = null;

    public static function createFromRequest(Request $request)
    {
        $self = new static;

        $self->userId = $request->user()?->id;
        $self->assessmentId = $request->assessmentId;

        return $self;
    }
}
