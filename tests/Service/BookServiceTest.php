<?php

namespace App\Tests\Service;

use App\Entity\Book;
use App\Exceptions\BookCategoryNotFountException;
use App\Model\BookListItem;
use App\Model\BookListResponse;
use App\Repository\BookCategoryRepository;
use App\Repository\BookRepository;
use App\Repository\ReviewRepository;
use App\Service\BookService;
use App\Service\RatingService;
use App\Tests\AbstractClassTest;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;

class BookServiceTest extends AbstractClassTest
{
    private RatingService $ratingService;
    private ReviewRepository $reviewRepository;
    private BookRepository $bookRepository;
    private BookCategoryRepository $bookCategoryRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->ratingService = $this->createMock(RatingService::class);
        $this->reviewRepository = $this->createMock(ReviewRepository::class);
        $this->bookRepository = $this->createMock(BookRepository::class);
        $this->bookCategoryRepository = $this->createMock(BookCategoryRepository::class);
    }

    public function testGetBooksByCategoryNotFound(): void
    {
        $this->bookCategoryRepository->expects($this->once())
            ->method('existsById')
            ->with(130)
            ->willReturn(false);
        $this->expectException(BookCategoryNotFountException::class);
        (new BookService($this->bookRepository, $this->bookCategoryRepository, $this->reviewRepository, $this->ratingService))->getBooksByCategory(130);
    }

    public function testGetBooksByCategory(): void
    {
        $this->bookRepository->expects($this->once())
            ->method('findBookByCategoryId')
            ->with(130)
            ->willReturn([$this->createBookEntity()]);

        $this->bookCategoryRepository->expects($this->once())
            ->method('existsById')
            ->with(130)
            ->willReturn(true);
        $service = new BookService($this->bookRepository, $this->bookCategoryRepository, $this->reviewRepository, $this->ratingService);
        $expected = new BookListResponse([$this->createBookItemModal()]);
        $this->assertEquals($expected, $service->getBooksByCategory(130));
    }

    private function createBookEntity(): Book
    {
        $book = (new Book())
            ->setTitle('Test-book')
            ->setSlug('test-book')
            ->setImage('http://localhost/test.png')
            ->setMeap(false)
            ->setIsbn('12345')
            ->setDescription('test descriptions')
            ->setAuthors(['Tester'])
            ->setCategories(new ArrayCollection())
            ->setPublicationDate(new DateTimeImmutable('2022-10-10'));
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
