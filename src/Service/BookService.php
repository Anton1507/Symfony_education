<?php

namespace App\Service;

use App\Entity\Book;
use App\Exceptions\BookCategoryNotFountException;
use App\Model\BookListItem;
use App\Model\BookListResponse;
use App\Repository\BookCategoryRepository;
use App\Repository\BookRepository;

class BookService
{
    public function __construct(private BookRepository $bookRepository, private BookCategoryRepository $bookCategoryRepository)
    {
    }

    public function getBooksByCategory(int $categoryId): BookListResponse
    {

        if (!$this->bookCategoryRepository->existsById($categoryId)) {
            throw new BookCategoryNotFountException();
        }

        return new BookListResponse(array_map(
            [$this, 'map'],
            $this->bookRepository->findBookByCategoryId($categoryId)
        ));
    }

    private function map(Book $book): BookListItem
    {
        return (new BookListItem())
            ->setId($book->getId())
            ->setTitle($book->getTitle())
            ->setSlug($book->getSlug())
            ->setAuthors($book->getAuthors())
            ->setImage($book->getImage())
            ->setMeap($book->getMeap())
            ->setPublicationDate($book->getPublicationDate()->getTimestamp());
    }
}
