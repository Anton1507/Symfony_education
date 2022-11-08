<?php

namespace App\Controller;

use App\Model\RecommendedBookListResponse;
use App\Service\RecommendationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;

class RecommendationController extends AbstractController
{
    public function __construct(private readonly RecommendationService $recommendationService)
    {
    }

    /**
     * @OA\Response
     * (
     *     response=200,
     *     description="Return recommendation book",
     *      @Model(type=RecommendedBookListResponse::class)
     * )
     */

    #[Route(path: '/api/v1/book/{id}/recommendations', methods: ['GET'])]
    public function recomendationsByBookId(int $id): Response
    {
        return $this->json($this->recommendationService->getRecommendationsByBookId($id));
    }
}
