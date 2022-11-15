<?php

namespace App\Controller;

use App\Attribute\RequestBody;
use App\Model\Author\BookListResponse;
use App\Model\Author\CreateBookRequest;
use App\Model\ErrorResponse;
use App\Service\AuthorService;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as QA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthorController extends AbstractController
{
    public function __construct(private AuthorService $authorService)
    {
    }

    /**
     * @QA\Tag(name="Author API")
     * @QA\Response(
     *     response=200,
     *     description="Get author owned books"
     * )
     */
    #[Route('/api/v1/author/books', methods: ['GET'])]
    public function books(): Response
    {
        return $this->json($this->authorService->getBooks());
    }

    /**
     * @QA\Tag(name="Author API")
     * @QA\Response(
     *     response=200,
     *     description="Create a book"
     * )
     * @QA\Response(
     *     response=404,
     *     description="validation failed",
     *     @Model(type=ErrorResponse::class)
     * )
     * @QA\RequestBody(@Model(type=CreateBookRequest::class))
     */
    #[Route('/api/v1/author/book', methods: ['POST'])]
    public function createBook(#[RequestBody] CreateBookRequest $request): Response
    {
        $this->authorService->createBook($request);

        return $this->json(null);
    }

    /**
     * @QA\Tag(name="Author API")
     * @QA\Response(
     *     response=200,
     *     description="Removes author's book"
     * )
     * @QA\Response(
     *     response=404,
     *     description="book not found",
     *      @Model(type=ErrorResponse::class)
     * )
     */
    #[Route('/api/v1/author/book/{id}', methods: ['DELETE'])]
    public function deleteBook(int $id): Response
    {
        $this->authorService->deleteBooks($id);

        return $this->json(null);
    }
}
