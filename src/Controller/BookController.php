<?php

namespace App\Controller;

use App\Model\BookListResponse;
use App\Model\ErrorResponse;
use App\Service\BookService;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as QA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
    public function __construct(private readonly BookService $bookService)
    {
    }

    /**
     * @QA\Response
     * (
     *     response=200,
     *     description="Return book for category id",
     *      @Model(type=BookListResponse::class)
     * )
     * @QA\Response
     * (
     *      response=404,
     *      description="Book category not found",
     *      @Model(type=ErrorResponse::class)
     * )
     */
    #[Route(path: 'api/v1/category/{id}/books', methods: ['GET'])]
    public function booksByCategory(int $id): Response
    {
        return $this->json($this->bookService->getBooksByCategory($id));
    }
}
