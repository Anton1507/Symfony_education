<?php

namespace App\Tests\Controller;

use App\Entity\Book;
use App\Entity\BookCategory;
use App\Entity\BookToBookFormat;
use App\Entity\BookFormat;
use App\Tests\AbstractControllerTest;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;

class BookControllerTest extends AbstractControllerTest
{
    public function testBooksByCategory()
    {
        $categoryId = $this->createCategory();
        $this->client->request('GET', '/api/v1/category/'.$categoryId.'/books');
        $responseContent = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertResponseIsSuccessful();
        $this->assertJsonDocumentMatchesSchema($responseContent, [
            'type' => 'object',
            'required' => 'items',
            'properties' => [
                'items' => [
                    'type' => 'array',
                    'items' => [
                        'type' => 'object',
                        'required' => ['id', 'title', 'slug', 'image', 'authors', 'meap', 'publicationDate'],
                        'properties' => [
                            'id' => ['type' => 'integer'],
                            'title' => ['type' => 'string'],
                            'image' => ['type' => 'string'],
                            'authors' => ['type' => 'array', 'items' => ['type' => 'string']],
                            'meap' => ['type' => 'boolean'],
                            'publicationDate' => ['type' => 'integer'],
                        ],
                    ],
                ],
            ],
        ]);
    }

    public function testBookById(): void
    {
        $bookId = $this->createBook();

        $this->client->request('GET', '/api/v1/book/'.$bookId);
        $responseContent = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertResponseIsSuccessful();
        $this->assertJsonDocumentMatchesSchema($responseContent, [
                'type' => 'object',
                'required' => ['id', 'title', 'slug', 'image', 'authors', 'meap', 'publicationDate', 'rating', 'reviews', 'categories', 'formats'],
                'properties' => [
                    'id' => ['type' => 'integer'],
                    'title' => ['type' => 'string'],
                    'image' => ['type' => 'string'],
                    'authors' => ['type' => 'array', 'items' => ['type' => 'string']],
                    'meap' => ['type' => 'boolean'],
                    'publicationDate' => ['type' => 'integer'],
                    'rating' => ['type' => 'integer'],
                    'reviews' => ['type' => 'integer'],
                    'categories' => [
                        'type' => 'array',
                        'required' => ['id', 'slug', 'title'],
                        'properties' => [
                            'title' => ['type' => 'string'],
                            'slug' => ['type' => 'string'],
                            'id' => ['type' => 'integer'],
                        ],
                    ],
                ],
        ]);
    }

    private function createCategory(): int
    {
        $bookCategory = (new BookCategory())->setTitle('Devices')->setSlug('devices');
        $this->em->persist($bookCategory);
        $this->em->flush();
        $this->em->persist((new Book())
            ->setTitle('Test Book')
            ->setImage('http://localhost/test.png')
            ->setSlug('test book')
            ->setIsbn('12345')
            ->setDescription('test')
            ->setAuthors(['Authors test'])
            ->setMeap(false)
            ->setPublicationDate(new DateTimeImmutable())
            ->setCategories(new ArrayCollection([$bookCategory])));
        $this->em->flush();

        return $bookCategory->getId();
    }

    private function createBook(): int
    {
        $bookCategory = (new BookCategory())->setTitle('Devices')->setSlug('devices');
        $this->em->persist($bookCategory);

        $format = (new BookFormat())
            ->setTitle('format')
            ->setDescription('description test')
            ->setComment(null);

        $this->em->persist($format);

        $book = (new Book())
            ->setTitle('Test Book')
            ->setImage('http://localhost/test.png')
            ->setSlug('test book')
            ->setIsbn('12345')
            ->setDescription('test')
            ->setAuthors(['Authors test'])
            ->setMeap(false)
            ->setPublicationDate(new DateTimeImmutable())
            ->setCategories(new ArrayCollection([]));
        $this->em->persist($book);

        $join = (new BookToBookFormat())
            ->setPrice(123.44)
            ->setFormat($format)
            ->setDiscountPercent(5)
            ->setBook($book);

        $this->em->persist($join);

        $this->em->flush();

        return $book->getId();
    }
}
