<?php

namespace App\Tests\Service;

use App\Entity\Book;
use App\Entity\BookCategory;
use App\Exceptions\BookCategoryNotFountException;
use App\Model\BookListItem;
use App\Model\BookListResponse;
use App\Repository\BookCategoryRepository;
use App\Repository\BookRepository;
use App\Service\BookService;
use App\Tests\AbstractClassTest;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;

class BookServiceTest extends AbstractClassTest
{
    public function testGetBooksByCategoryNotFound(): void
    {
        $bookRepository = $this->createMock(BookRepository::class);
        $bookCategoryRepository = $this->createMock(BookCategoryRepository::class);
        $bookCategoryRepository->expects($this->once())
            ->method('existsById')
            ->with(130)
            ->willReturn(false);
        $this->expectException(BookCategoryNotFountException::class);
        (new BookService($bookRepository, $bookCategoryRepository))->getBooksByCategory(130);
    }

    public function testGetBooksByCategory(): void
    {
        $bookRepository = $this->createMock(BookRepository::class);
        $bookRepository->expects($this->once())
            ->method('findBookByCategoryId')
            ->with(130)
            ->willReturn([$this->createBookEntity()]);
        $bookCategoryRepository = $this->createMock(BookCategoryRepository::class);
        $bookCategoryRepository->expects($this->once())
            ->method('existsById')
            ->with(130)
            ->willReturn(true);
        $servise = new BookService($bookRepository, $bookCategoryRepository);
        $expected = new BookListResponse([$this->createBookItemModal()]);
        $this->assertEquals($expected, $servise->getBooksByCategory(130));
    }

    private function createBookEntity()
    {
        $book = (new Book())
            ->setTitle('Test-book')
            ->setSlug('test-book')
            ->setImage('http://localhost/test.png')
            ->setMeap(false)
            ->setAuthors(['Tester'])
            ->setCategories(new ArrayCollection())
            ->setPublicationDate(new DateTime('2022-10-10'));
        $this->setEntityId($book, 123);

        return $book;
    }

    private function createBookItemModal(): BookListItem
    {
        return (new BookListItem())
            ->setId(123)
            ->setTitle('Test-book')
            ->setSlug('test-book')
            ->setImage('http://localhost/test.png')
            ->setMeap(false)
            ->setAuthors(['Tester'])
            ->setPublicationDate(1665352800);
    }
}
