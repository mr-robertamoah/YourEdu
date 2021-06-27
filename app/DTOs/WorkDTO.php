<?php

namespace App\DTOs;

use App\Traits\DTOTrait;
use App\YourEdu\Assessment;
use App\YourEdu\Work;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class WorkDTO
{
    use DTOTrait;

    public ?string $status = null;
    public ?Model $addedby = null;
    public ?Work $work = null;
    public ?Assessment $assessment = null;
    public ?string $reportDetailId = null;
    public ?string $assessmentId = null;
    public bool $accessChecked = false;
    public bool $dontBroadcast = false;

    public static function createFromRequest(Request $request)
    {
        $self = new static;

        $self->userId = $request->user()?->id;
        $self->assessmentId = $request->assessmentId;

        return $self;
    }
}
