<?php

namespace App\Tests\Service;

use App\Entity\User;
use App\Exceptions\UserAlreadyExistException;
use App\Model\SignUpRequest;
use App\Repository\UserRepository;
use App\Service\SignUpService;
use App\Tests\AbstractClassTest;
use Doctrine\ORM\EntityManagerInterface;

use Lexik\Bundle\JWTAuthenticationBundle\Security\Http\Authentication\AuthenticationSuccessHandler;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;

class SignUpServiceTest extends AbstractClassTest
{
    private UserPasswordHasher $hasher;
    private UserRepository $userRepository;
    private EntityManagerInterface $em;
    private AuthenticationSuccessHandler $successHandler;

    protected function setUp(): void
    {
        parent::setUp();
        $this->hasher = $this->createMock(UserPasswordHasher::class);
        $this->userRepository = $this->createMock(UserRepository::class);
        $this->em = $this->createMock(EntityManagerInterface::class);
        $this->successHandler = $this->createMock(AuthenticationSuccessHandler::class);
    }

    private function createService(): SignUpService
    {
        return new SignUpService($this->hasher, $this->userRepository, $this->em, $this->successHandler);
    }

    public function testSingUpUserAlreadyExist(): void
    {
        $this->expectException(UserAlreadyExistException::class);

        $this->userRepository->expects($this->once())
            ->method('existsByEmail')
            ->with('test@test.com')
            ->willReturn(true);
        $this->createService()->singUp((new SignUpRequest())->setEmail('test@test.com'));
    }

    public function testSingUp(): void
    {
        $response = new Response();
        $expectedHasherdUser = (new User())
            ->setRoles(['ROLE_USER'])
            ->setEmail('test@test.com')
            ->setFirstName('Vasya')
            ->setLastName('Testov');
        $expectedUser = clone $expectedHasherdUser;
        $expectedUser->setPassword('hashpassword');

        $this->userRepository->expects($this->once())
            ->method('existsByEmail')
            ->with('test@test.com')
            ->willReturn(false);

        $this->hasher->expects($this->once())
            ->method('hashPassword')
            ->with($expectedHasherdUser, 'testTest')
            ->willReturn('hashpassword');

        $this->em->expects($this->once())->method('persist')->with($expectedUser);
        $this->em->expects($this->once())->method('flush');

        $this->successHandler->expects($this->once())
            ->method('handleAuthenticationSuccess')
            ->with($expectedUser)
            ->willReturn($response);

        $singUpRequest = (new SignUpRequest())
            ->setFirstName('Vasya')
            ->setLastName('Testov')
            ->setEmail('test@test.com')
            ->setPassword('testTest');

        $this->assertEquals($response, $this->createService()->singUp($singUpRequest));
    }
}
