<?php

namespace App\Security;

use App\Entity\User;
use App\Repository\UserRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Security\User\PayloadAwareUserProviderInterface;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;

class JwtUserProvider implements PayloadAwareUserProviderInterface
{
    public function __construct(private readonly UserRepository $userRepository)
    {
    }

    public function loadUserByUsernameAndPayload(string $username, array $payload)
    {
        return null;
    }

    public function refreshUser(UserInterface $user): ?UserInterface
    {
        return null;
    }

    public function supportsClass(string $class): bool
    {
        return User::class == $class || is_subclass_of($class, User::class);
    }

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        return $this->getUser('email', $identifier);
    }

    public function loadUserByIdentifierAndPayload(string $indentifier, array $payload): UserInterface
    {
        return $this->getUser('id', $payload['id']);
    }

    private function getUser($key, $value): UserInterface
    {
        $user = $this->userRepository->findOneBy([$key => $value]);
        if (null == $user) {
            $e = new UserNotFoundException('User with id '.json_encode($value.'not foynd'));
            $e->setUserIdentifier(json_encode($value));

            throw $e;
        }

        return $user;
    }
}
