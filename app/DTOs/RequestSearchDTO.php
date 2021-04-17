<?php

namespace App\DTOs;

use Illuminate\Http\Request;

class RequestSearchDTO
{
    public ?string $account = null;
    public ?string $accountId = null;
    public ?string $userId = null;
    public ?string $type = null;
    public ?string $search = null;
    public ?bool $others = false;
    
    public static function createFromRequest(Request $request)
    {
        $self = new static;
        
        $self->others = $request->others ? 
            json_decode($request->others) : false;
        $self->account = $request->account;
        $self->type = $request->type;
        $self->search = $request->search ?: '';
        $self->accountId = $request->accountId;
        $self->userId = (int) $request->user()?->id;

        return $self;
    }
}
