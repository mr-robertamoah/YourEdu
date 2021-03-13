<?php

namespace App\DTOs;

use App\Contracts\PostTypeDTOContract;
use App\YourEdu\Poem;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class PoemDTO extends PostTypeDTOContract
{
    public ?Carbon $publishedAt = null;
    public string | null $about;
    public string | null $poemId;
    public string | null $title;
    public array $files;
    public array $removedFiles;
    public string | null $authorNames;
    public array $sections;
    public array $removedSections;
    public array $editedSections;
    public ?Poem $poem = null;
    public ?Model $addedby = null;
    public ?Model $poemable = null;

    public static function new()
    {
        return new static;
    }

    public static function createFromRequest(Request $request)
    {
        $self = new static();

        $self->poemId = $request->poemId;
        $self->title = $request->title;
        $self->authorNames = $request->authorNames;
        $self->publishedAt = $request->published ?
            Carbon::parse($request->published) : null;
        $self->about = $request->about;
        $self->sections = $request->sections ?
            PoemSectionDTO::createFromArray(
                json_decode($request->sections)
            ) : [];
        $self->removedSections = $request->removedSections ?
            PoemSectionDTO::createFromArray(
                json_decode($request->removedSections)
            ) : [];
        $self->editedSections = $request->editedSections ?
            PoemSectionDTO::createFromArray(
                json_decode($request->editedSections)
            ) : [];
        $self->files = $request->hasFile('typeFiles') ?
            $request->file('typeFiles') : [];
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

    public function withPoem(Model $poem)
    {
        $clone = clone $this;

        $clone->poem = $poem;

        return $clone;
    }

    public function resetFiles()
    {
        $clone = clone $this;

        $clone->files = [];

        return $clone;
    }
}
