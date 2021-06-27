<?php

namespace App\DTOs;

use App\Traits\DTOTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class RiddleDTO
{
    use DTOTrait;
    
    public ?Carbon $publishedAt = null;
    public ?string $description = null;
    public ?string $riddleId = null;
    public array $files = [];
    public array $removedFiles = [];
    public ?string $body = null;
    public ?string $authorNames = null;
    public ?array $authorAccounts = null;
    public ?int $scoreOver = null;
    public ?Model $addedby = null;
    public ?Model $riddleable = null;
    public ?Collection $authors = null;
    public ?Model $riddle = null;

    public static function createFromRequest(Request $request)
    {
        $self = new static();

        $self->riddleId = $request->riddleId;
        $self->body = $request->body;
        $self->authorNames = $request->authorNames;
        $self->publishedAt = $request->published ?
            Carbon::parse($request->published) : null;
        $self->description = $request->description;
        $self->scoreOver = (int)$request->scoreOver;
        if ($self->scoreOver > 100) {
            $self->scoreOver = 100;
        } else if ($self->scoreOver < 5) {
            $self->scoreOver = 5;
        }
        $self->authorAccounts = $request->authorAccounts ? 
            ModelDTO::createFromArray(
                json_decode($request->authorAccounts)
            ) : [];
        $self->files = $request->hasFile('typeFiles') ?
            $request->file('typeFiles') : [];
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
