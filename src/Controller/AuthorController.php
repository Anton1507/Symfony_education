<?php

namespace App\Controller;

use App\Attribute\RequestBody;
use App\Attribute\RequestFile;
use App\Model\Author\BookListResponse;
use App\Model\Author\CreateBookRequest;
use App\Model\Author\PublishBookRequest;
use App\Model\Author\UploadCoverResponse;
use App\Model\ErrorResponse;
use App\Service\AuthorService;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as QA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotNull;

class AuthorController extends AbstractController
{
    public function __construct(private AuthorService $authorService)
    {
    }

    /**
     * @QA\Tag(name="Author API")
     * @QA\Response(
     *     response=200,
     *     description="Publish a book"
     * )
     * @QA\Response(
     *     response=404,
     *     description="validation failed",
     *     @Model(type=ErrorResponse::class)
     * )
     * @QA\RequestBody(@Model(type=PublishBookRequest::class))
     */
    #[Route('/api/v1/author/book/{id}/publish', methods: ['POST'])]
    public function publish(int $id, #[RequestBody] PublishBookRequest $request): Response
    {
        $this->authorService->publish($id, $request);

        return $this->json(null);
    }

    /**
     * @QA\Tag(name="Author API")
     * @QA\Response(
     *     response=200,
     *     description="Unpublish a book"
     * )
     */
    #[Route('/api/v1/author/book/{id}/unpublish', methods: ['POST'])]
    public function unpublish(int $id): Response
    {
        $this->authorService->unpublish($id);

        return $this->json(null);
    }

    /**
     * @QA\Tag(name="Author API")
     * @QA\Response(
     *     response=200,
     *     description="Get author owned books",
     *     @Model(type=BookListResponse::class)
     *
     * )
     */
    #[Route(path: '/api/v1/author/books', methods: ['GET'])]
    public function books(): Response
    {
        return $this->json($this->authorService->getBooks());
    }

    /**
     * @QA\Tag(name="Author API")
     * @QA\Response(
     *     response=200,
     *     description="Upload book cover",
     *     @Model(type=UploadCoverResponse::class)
     * )
     * @QA\Response (
     *     response=400,
     *     description="Validation failed",
     *     @Model(type=ErrorResponse::class)
     * )
     */
    #[Route(path: '/api/v1/author/book/{id}/uploadCover', methods: ['POST'])]
    public function uploadCover(
        int $id,
        #[RequestFile(field: 'cover', constraints: [
            new Image(maxSize: '1M', mimeTypes: ['image/jpeg', 'image/png', 'image/jpg']),
        ])] UploadedFile $file
    ): Response {
        return $this->json($this->authorService->uploadCover($id, $file));
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
