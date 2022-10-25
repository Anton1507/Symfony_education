<?php

namespace App\Tests\Controller;

use App\Entity\Book;
use App\Entity\BookCategory;
use App\Tests\AbstractControllerTest;
use Doctrine\Common\Collections\ArrayCollection;
use DateTime;

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
                        'propreties' => [
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

    private function createCategory(): int
    {
        $bookCategory = (new BookCategory())->setTitle('Devices')->setSlug('devices');
        $this->em->persist($bookCategory);
        $this->em->flush();
        $this->em->persist((new Book())
            ->setTitle('Test Book')
            ->setImage('http://localhost/test.png')
            ->setSlug('test book')
            ->setAuthors(['Authors test'])
            ->setMeap(false)
            ->setPublicationDate(new DateTime())
            ->setCategories(new ArrayCollection([$bookCategory])));
        $this->em->flush();

        return $bookCategory->getId();
    }
}
