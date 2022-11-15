<?php

namespace App\Controller;

use App\Service\RoleService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use OpenApi\Annotations as QA;
use Symfony\Component\Routing\Annotation\Route;
use App\Model\ErrorResponse;
use Nelmio\ApiDocBundle\Annotation\Model;

class AdminController extends AbstractController
{
    public function __construct(private RoleService $roleService)
    {
    }

    /**
     * @QA\Tag(name="Admin API")
     * @QA\Response(
     *     response=200,
     *     description="Grants ROLE_AUTHOR to a user"
     * )
     * @QA\Response(
     *     response=404,
     *     description="User mot found",
     *     @Model(type=ErrorResponse::class)
     * )
     */
    #[Route('/api/v1/admin/grantAuthor/{userId}', methods: ['POST'])]
    public function grantAuthor(int $userId): Response
    {
        $this->roleService->grantAuthor($userId);

        return $this->json(null);
    }
}
