<?php

namespace App\Services;

use App\DTOs\BookDTO;
use App\Exceptions\AccountNotFoundException;
use App\Exceptions\BookException;
use App\YourEdu\Book;

class BookService
{
    public function createBook(BookDTO $bookDTO)
    {
        $book = $this->createOrUpdateBook($bookDTO, 'create');

        $bookDTO = $bookDTO->withBook($book);

        $book = $this->attachBookToItem(
            book: $book,
            bookDTO: $bookDTO
        );

        $book = $this->addBookFiles($book, $bookDTO);

        return $book;
    }

    private function createOrUpdateBook
    (
        BookDTO $bookDTO,
        string $method
    ) : Book
    {
        $data = [
            'title' => $bookDTO->title,
            'author_names' => $bookDTO->authorNames,
            'about' => $bookDTO->about,
            'published_at' => $bookDTO->publishedAt?->toDateTimeString(),
        ];

        $book = null;

        if ($method === 'create') {
            $book = $bookDTO->addedby->booksAdded()
                ->create($data);
        }
        
        if ($method === 'update') {
            $book = $this->getBookModel($bookDTO);

            $book?->update($data);
        }
        
        if (is_null($book)) {
            $this->throwBookException(
                message: "failed to {$method} book.",
                data: $bookDTO
            );
        }

        return $book->refresh();
    }

    private function addBookFiles
    (
        Book $book,
        BookDTO $bookDTO,
    )
    {
        foreach ($bookDTO->files as $file) {

            FileService::createAndAttachFiles(
                account: $bookDTO->addedby, 
                file: $file,
                item: $book
            );
        }

        return $book->refresh();
    }

    private function attachBookToItem
    (
        Book $book,
        BookDTO $bookDTO
    ) : Book
    {
        if (!$bookDTO->bookable) return $book;

        $book->bookable()->associate($bookDTO->bookable);
        $book->save();

        return $book->refresh();
    }

    private function throwBookException
    (
        string $message,
        $data = null
    )
    {
        throw new BookException(
            message: $message,
            data: $data
        );
    }

    public function updateBook(BookDTO $bookDTO)
    {
        $book = $this->createOrUpdateBook($bookDTO, 'update');

        $bookDTO = $bookDTO->withBook($book);

        $book = $this->addBookFiles($book, $bookDTO);

        $book = $this->removeBookFiles($book, $bookDTO);

        return $book;
    }

    private function removeBookFiles
    (
        Book $book,
        BookDTO $bookDTO
    )
    {
        foreach ($bookDTO->removedFiles as $file) {

            FileService::deleteAndUnattachFiles(
                file: $file,
                item: $book
            );
        }

        return $book->refresh();
    }

    public function deleteBook(BookDTO $bookDTO)
    {
        $book = $this->getBookModel($bookDTO);

        $book = $this->deleteBookFiles($book);

        return $book->delete();
    }

    private function getBookModel(BookDTO $bookDTO)
    {
        if ($bookDTO->book) {
            return $bookDTO->book;
        }

        $book = getYourEduModel('book', $bookDTO->bookId);
        if (is_null($book)) {
            throw new AccountNotFoundException("book with id {$bookDTO->bookId} not found.");
        }

        return $book;
    }

    private function deleteBookFiles
    (
        Book $book,
    ) : Book
    {
        FileService::deleteYourEduItemFiles(
            item: $book,
        );

        return $book->refresh();
    }
}
