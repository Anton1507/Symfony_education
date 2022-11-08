<?php

namespace App\Tests\Controller;

use App\Entity\Book;
use App\Entity\Review;
use App\Tests\AbstractControllerTest;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;

class ReviewControllerTest extends AbstractControllerTest
{
    public function testReviews()
    {
        $book = $this->createBook();
        $this->createReview($book);
        $this->em->flush();

        $this->client->request('GET', '/api/v1/book/'.$book->getId().'/reviews');
        $responseContent = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertResponseIsSuccessful();
        $this->assertJsonDocumentMatchesSchema($responseContent, [
            'type' => 'object',
            'required' => ['items', 'rating', 'page', 'pages', 'perPage', 'total'],
            'properties' => [
                'rating' => ['type' => 'number'],
                'page' => ['type' => 'integer'],
                'pages' => ['type' => 'integer'],
                'perPage' => ['type' => 'integer'],
                'total' => ['type' => 'integer'],
                'items' => [
                    'type' => 'array',
                    'items' => [
                        'type' => 'object',
                        'required' => ['id', 'content', 'author', 'rating', 'creatAt'],
                        'properties' => [
                            'id' => ['type' => 'integer'],
                            'rating' => ['type' => 'integer'],
                            'creatAt' => ['type' => 'integer'],
                            'content' => ['type' => 'string'],
                            'author' => ['type' => 'string'],
                        ],
                    ],
                ],
            ],
        ]);
    }
    private function createBook(): Book
    {
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

        return $book;
    }

    private function createReview(Book $book)
    {
        $this->em->persist((new Review())
            ->setAuthor('tester')
            ->setContent('tets content')
            ->setCreatedAt(new DateTimeImmutable())
            ->setRating(5)
            ->setBook($book)
        );
    }
}
