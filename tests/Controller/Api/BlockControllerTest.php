<?php

namespace App\Tests\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BlockControllerTest extends WebTestCase
{
    public function testBlockList(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/blocks');
        $this->assertResponseIsSuccessful();
    }
}
