<?php

namespace App\DTOs;

use App\Traits\DTOTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class ActivityDTO
{
    use DTOTrait;

    public ?Carbon $publishedAt = null;
    public ?string $description = null;
    public ?string $activityId = null;
    public array $files = [];
    public array $removedFiles = [];
    public ?Model $activity = null;
    public ?Model $addedby = null;
    public ?Model $activityfor = null;

    public static function createFromRequest(Request $request)
    {
        $self = new static();

        $self->activityId = $request->activityId;
        $self->publishedAt = $request->published ?
            Carbon::parse($request->published) : null;
        $self->description = $request->description;
        $self->files = $request->hasFile('typeFiles') ?
            $request->file('previewFile') : [];
        $self->removedFiles = $request->removedTypeFiles ?
            FileDTO::createFromArray(
                json_decode($request->removedTypeFiles)
            ) : [];

        return $self;
    }

    public function resetFiles()
    {
        $clone = clone $this;

        $clone->files = [];

        return $clone;
    }
}
