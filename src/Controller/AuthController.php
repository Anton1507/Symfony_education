<?php

namespace App\Controller;

use App\Attribute\RequestBody;
use App\Model\ErrorResponse;
use App\Model\IdResponse;
use App\Model\SignUpRequest;
use App\Service\SignUpService;
use Lexik\Bundle\JWTAuthenticationBundle\Response\JWTAuthenticationSuccessResponse;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as QA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthController extends AbstractController
{
    public function __construct(private readonly SignUpService $signUpService)
    {
    }

    /**
     * @QA\Response(
     *     response=200,
     *     description="Signs up a user",
     * @Model(type=IdResponse::class)
     * )
     * @QA\Response(
     *     response=409,
     *     description="User already exist",
     * @Model(type=ErrorResponse::class)
     * )
     * @QA\Response(
     *     response=409,
     *     description="Validation failed",
     * @Model(type=ErrorResponse::class)
     * )
     * @QA\RequestBody(@Model(type=SignUpRequest::class))
     */
    #[Route(path: '/api/v1/auth/signUp', methods: ['POST'])]
    public function action(#[RequestBody] SignUpRequest $signUpRequest): JWTAuthenticationSuccessResponse|Response
    {
        return $this->signUpService->singUp($signUpRequest);
    }
}
