<?php

namespace App\Tests\Controller;

use App\Entity\Book;
use App\Tests\AbstractControllerTest;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Hoverfly\Client as HoverflyClient;
use Hoverfly\Model\RequestFieldMatcher;
use Hoverfly\Model\Response;

class RecommendationControllerTest extends AbstractControllerTest
{
    private HoverflyClient $hoverfly;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpHoverfly();
    }

    public function testRecommendationByBookId()
    {
        $bookId = $this->createBook();
        $requestId = 123;

        $this->hoverfly->simulate(
            $this->hoverfly->buildSimulation()
                            ->service()
                            ->get(new RequestFieldMatcher('/api/v1/book/'.$requestId.'/recommendations', RequestFieldMatcher::GLOB))
                            ->headerExact('Authorization', 'Bearer test')
            ->willReturn(Response::json([
                'ts' => 12345,
                'id' => $requestId,
                'recommendations' => [['id' => $bookId]],
            ]))
        );

        $this->client->request('GET', '/api/v1/book/123/recommendations');
        $responseContent = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertResponseIsSuccessful();
        $this->assertJsonDocumentMatchesSchema($responseContent, [
            'type' => 'object',
            'required' => ['items'],
            'properties' => [
                'items' => [
                    'type' => 'array',
                    'items' => [
                        'type' => 'object',
                        'required' => ['id', 'title', 'slug', 'image', 'shortDescription'],
                        'properties' => [
                            'id' => ['type' => 'integer'],
                            'title' => ['type' => 'string'],
                            'slug' => ['type' => 'string'],
                            'image' => ['type' => 'string'],
                            'shortDescription' => ['type' => 'string'],
                        ],
                    ],
                ],
            ],
        ]);
    }

    private function createBook(): int
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
        $this->em->flush();

        return $book->getId();
    }

    private function setUpHoverfly()
    {
        $this->hoverfly = new HoverflyClient(['base_uri' => $_ENV['HOVERFLY_API']]);
        $this->hoverfly->deleteJournal();
        $this->hoverfly->deleteSimulation();
    }
}
