<?php

namespace App\DTOs;

use App\Contracts\PostTypeDTOContract;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class BookDTO extends PostTypeDTOContract
{
    public ?Carbon $publishedAt = null;
    public string | null $about;
    public string | null $authorNames;
    public array | null $authorAccounts;
    public ?Collection $authors = null;
    public ?Model $addedby = null;
    public string | null $title;
    public string | null $bookId;
    public array $files;
    public array $removedFiles;
    public ?Model $book = null;
    public ?Model $bookable = null;

    public static function new()
    {
        return new static;
    }

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

    public function withAddedby(Model $addedby)
    {
        $clone = clone $this;

        $clone->addedby = $addedby;

        return $clone;
    }

    public function withBook(Model $book)
    {
        $clone = clone $this;

        $clone->book = $book;

        return $clone;
    }

    public function resetFiles()
    {
        $clone = clone $this;

        $clone->files = [];

        return $clone;
    }
}
