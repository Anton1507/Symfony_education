<?php

namespace App\Tests\Service;

use App\Repository\ReviewRepository;
use App\Service\RatingService;
use App\Tests\AbstractClassTest;

class RatingServiceTest extends AbstractClassTest
{
    private ReviewRepository $reviewRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->reviewRepository = $this->createMock(ReviewRepository::class);
    }

    public function provider(): array
    {
        return [
            [25, 20, 1.25],
            [60, 20, 3],
            [30, 10, 3],
            [45, 15, 3],
            [0, 5, 0],
        ];
    }

    /**
     * @dataProvider provider
     */
    public function testCalcReviewRatingForBook(int $repositoryRatingSum, int $total, float $expectedRating): void
    {
        $this->reviewRepository->expects($this->once())
            ->method('getBookTotalRatingSum')
            ->with(1)
            ->willReturn($repositoryRatingSum);

        $actual = (new RatingService($this->reviewRepository))->calcReviewRatingForBook(1, $total);
        $this->assertEquals($expectedRating, $actual);
    }

    public function testCalcReviewRatingForBookIfTotalZero(): void
    {
        $this->reviewRepository->expects($this->never())
            ->method('getBookTotalRatingSum');
        $this->assertEquals(0, (new RatingService($this->reviewRepository))->calcReviewRatingForBook(1, 0));
    }
}
