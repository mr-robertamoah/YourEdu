<?php

namespace App\DTOs;

use Illuminate\Http\Request;

class ResponseDTO
{
    public ?string $requestId = null;
    public ?string $action = null;
    public ?string $response = null;
    public ?string $userId = null;

    public static function createFromRequest(Request $request)
    {
        $self = new static;

        $self->response = $request->response;
        $self->action = $request->action;
        $self->userId = $request->user()?->id;
        $self->requestId = $request->requestId;

        return $self;
    }
}
