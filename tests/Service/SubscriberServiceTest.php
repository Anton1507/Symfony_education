<?php

namespace App\Tests\Service;

use App\Entity\Subscriber;
use App\Exceptions\SubscriberAlreadyExistEmail;
use App\Model\SubscriberRequest;
use App\Repository\SubscriberRepository;
use App\Service\SubscriberService;
use App\Tests\AbstractClassTest;
use Doctrine\ORM\EntityManager;

class SubscriberServiceTest extends AbstractClassTest
{
    private SubscriberRepository $repository;
    private EntityManager $em;
    private const EMAIL = 'test@gmail.com';

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = $this->createMock(SubscriberRepository::class);
        $this->em = $this->createMock(EntityManager::class);
    }

    public function testSubscribeAlreadyExists(): void
    {
        $this->expectException(SubscriberAlreadyExistEmail::class);

        $this->repository->expects($this->once())
            ->method('existByEmail')
            ->with(self::EMAIL)
            ->willReturn(true);
        $request = new SubscriberRequest();
        $request->setEmail(self::EMAIL);

        (new SubscriberService($this->repository, $this->em))->subscribe($request);
    }

    public function testSubscribe(): void
    {
        $this->repository->expects($this->once())
            ->method('existByEmail')
            ->with(self::EMAIL)
            ->willReturn(false);
        $expectedSubscriber = new Subscriber();
        $expectedSubscriber->setEmail(self::EMAIL);

        $this->em->expects($this->once())
            ->method('persist')
            ->with($expectedSubscriber);
        $this->em->expects($this->once())
            ->method('flush');

        $request = new SubscriberRequest();
        $request->setEmail(self::EMAIL);

        (new SubscriberService($this->repository, $this->em))->subscribe($request);
    }
}
