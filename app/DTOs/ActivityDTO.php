<?php

namespace App\DTOs;

use App\Contracts\PostTypeDTOContract;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class ActivityDTO extends PostTypeDTOContract
{
    public ?Carbon $publishedAt = null;
    public string | null $description;
    public string | null $activityId;
    public array $files;
    public array $removedFiles;
    public ?Model $activity = null;
    public ?Model $addedby = null;
    public ?Model $activityfor = null;

    public static function new()
    {
        return new static;
    }

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

    public function withAddedby(Model $addedby)
    {
        $clone = clone $this;

        $clone->addedby = $addedby;

        return $clone;
    }

    public function withActivity(Model $activity)
    {
        $clone = clone $this;

        $clone->activity = $activity;

        return $clone;
    }

    public function resetFiles()
    {
        $clone = clone $this;

        $clone->files = [];

        return $clone;
    }
}
