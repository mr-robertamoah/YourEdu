<?php

namespace App\DTOs;

use App\Traits\DTOTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class BookDTO
{
    use DTOTrait;

    public ?Carbon $publishedAt = null;
    public ?string $about = null;
    public ?string $authorNames = null;
    public ?array $authorAccounts = null;
    public ?Collection $authors = null;
    public ?Model $addedby = null;
    public ?string $title = null;
    public ?string $bookId = null;
    public array $files;
    public array $removedFiles;
    public ?Model $book = null;
    public ?Model $bookable = null;

    public static function createFromRequest(Request $request)
    {
        $self = new static();

        $self->bookId = $request->bookId;
        $self->publishedAt = $request->published ?
            Carbon::parse($request->published) : null;
        $self->title = $request->title;
        $self->authorNames = $request->author;
        $self->about = $request->about;
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
