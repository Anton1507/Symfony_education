<?php

namespace App\Tests\Controller;

use App\Tests\AbstractControllerTest;

class AdminControllerTest extends AbstractControllerTest
{
    public function testGrantAuthor()
    {
        $username = 'test@test.com';
        $password = '123456789';
        $user = $this->createUser('TestUser@test.com', 'testtest');
        $admin = $this->createAdmin($username, $password);
        $this->auth($username, $password);

        $this->client->request('POST', '/api/v1/admin/grantAuthor/'.$user->getId());
        $this->assertResponseIsSuccessful();
    }
}
