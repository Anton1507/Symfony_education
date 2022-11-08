<?php

namespace App\Tests\Service;

use App\Entity\Book;
use App\Entity\BookCategory;
use App\Entity\BookFormat;
use App\Entity\BookToBookFormat;
use App\Exceptions\BookCategoryNotFountException;
use App\Model\BookCategory as BookCategoryModel;
use App\Model\BookDetails;
use App\Model\BookFormat as BookFormatModel;
use App\Model\BookListItem;
use App\Model\BookListResponse;
use App\Repository\BookCategoryRepository;
use App\Repository\BookRepository;
use App\Service\BookService;
use App\Service\Rating;
use App\Service\RatingService;
use App\Tests\AbstractClassTest;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;

class BookServiceTest extends AbstractClassTest
{
    private RatingService $ratingService;
    private BookRepository $bookRepository;
    private BookCategoryRepository $bookCategoryRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->ratingService = $this->createMock(RatingService::class);
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
        (new BookService($this->bookRepository, $this->bookCategoryRepository, $this->ratingService))->getBooksByCategory(130);
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
        $service = new BookService($this->bookRepository, $this->bookCategoryRepository, $this->ratingService);
        $expected = new BookListResponse([$this->createBookItemModal()]);
        $this->assertEquals($expected, $service->getBooksByCategory(130));
    }

    public function testgetBookById(): void
    {
        $this->bookRepository->expects($this->once())
            ->method('getById')
            ->with(123)
            ->willReturn($this->createBookEntity());

        $this->ratingService->expects($this->once())
            ->method('calcReviewRatingForBook')
            ->with(123)
            ->willReturn(new Rating(10, 5.5));

        $format = (new BookFormatModel())
                        ->setId(1)
                        ->setTitle('format')
                        ->setDescription('descriptions format')
                        ->setComment('formatComment')
                        ->setPrice(2.34)
                        ->setDiscountPrice(4);
        $expected = (new BookDetails())->setId(123)
            ->setRating(5.0)
            ->setReviews(10)
            ->setSlug('test-book')
            ->setTitle('Test-book')
            ->setImage('http://localhost/test.png')
            ->setAuthors(['Tester'])
            ->setMeap(false)
            ->setCategories([new BookCategoryModel(1, 'Category', 'category slug')])
            ->setPublicationDate('1665352800')
            ->setFormats([$format]);

        $this->assertEquals($expected, (new BookService($this->bookRepository, $this->bookCategoryRepository, $this->ratingService))->getBookById(123));
    }

    private function createBookEntity(): Book
    {
        $category = (new BookCategory())->setTitle('Category')->setSlug('category slug');
        $this->setEntityId($category, 1);

        $format = (new BookFormat())->setTitle('format')->setComment('formatComment')->setDescription('descriptions format');
        $this->setEntityId($format, 1);

        $join = (new BookToBookFormat())->setPrice(2.34)->setFormat($format)->setDiscountPercent(4);
        $this->setEntityId($join, 1);

        $book = (new Book())
            ->setTitle('Test-book')
            ->setSlug('test-book')
            ->setImage('http://localhost/test.png')
            ->setMeap(false)
            ->setIsbn('12345')
            ->setDescription('test descriptions')
            ->setAuthors(['Tester'])
            ->setCategories(new ArrayCollection([$category]))
            ->setPublicationDate(new DateTimeImmutable('2022-10-10'))
            ->setFormats(new ArrayCollection([$join]));

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
