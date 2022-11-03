<?php

namespace App\Tests\Controller;

use App\Tests\AbstractControllerTest;
use Symfony\Component\HttpFoundation\Response;

class SubscribeControllerTest extends AbstractControllerTest
{
    public function testSubscriber(): void
    {
        $content = json_encode(['email' => 'testing123@gmail.com', 'agreed' => true]);
        $this->client->request('POST', '/api/v1/subscribe', [], [], [], $content);

        $this->assertResponseIsSuccessful();
    }

    public function testSubscriberAgreed(): void
    {
        $content = json_encode(['email' => 'testc2@gmail.com']);
        $this->client->request('POST', '/api/v1/subscribe', [], [], [], $content);

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);

    }
}
