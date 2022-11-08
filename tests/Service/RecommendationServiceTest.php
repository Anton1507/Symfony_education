<?php

namespace App\Tests\Service;

use App\Entity\Book;
use App\Model\RecommendedBook;
use App\Model\RecommendedBookListResponse;
use App\Repository\BookRepository;
use App\Service\Recommendation\Model\RecommendationItem;
use App\Service\Recommendation\Model\RecommendationResponse;
use App\Service\Recommendation\RecommendationApiService;
use App\Service\RecommendationService;
use App\Tests\AbstractClassTest;

class RecommendationServiceTest extends AbstractClassTest
{
    private BookRepository $bookRepository;
    private RecommendationApiService $recommendationApiService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->recommendationApiService = $this->createMock(RecommendationApiService::class);
        $this->bookRepository = $this->createMock(BookRepository::class);
    }

    private function dataProvider()
    {
        return [
            ['short descriptions', 'short descriptions'],
            [
                <<<EOF
                begin long descriptions long descriptions 
                long descriptions long descriptions long descriptions 
                long descriptions long descriptions long descriptions long descriptions 
                long descriptions long descriptions 
                long descriptions long descriptions 
                EOF,
                <<<EOF
                begin long descriptions long descriptions 
                long descriptions long descriptions long descriptions 
                long descriptions long descriptions long descript...
                EOF,
            ],
        ];
    }

    /**
     * @dataProvider dataProvider
     */
    public function testGetRecommendationsByBookId(string $actualDescription, string $expectedDescription)
    {
        $entity = (new Book())->setImage('image')->setSlug('slug')->setTitle('title')->setDescription($actualDescription);
        $this->setEntityId($entity, 2);
        $this->bookRepository->expects($this->once())
            ->method('findBooksByIds')
            ->with([2])
            ->willReturn([$entity]);
        $this->recommendationApiService->expects($this->once())
            ->method('getRecommendationsByBookId')
            ->with(1)
            ->willReturn(new RecommendationResponse(1, 12123, [
                new RecommendationItem(2),
            ]));

        $expected = new RecommendedBookListResponse([
            (new RecommendedBook())->setTitle('title')->setSlug('slug')->setImage('image')->setId(2)->setShortDescription($expectedDescription),
        ]);
        $this->assertEquals($expected, $this->createService()->getRecommendationsByBookId(1));
    }

    private function createService(): RecommendationService
    {
        return new RecommendationService($this->bookRepository, $this->recommendationApiService);
    }
}
