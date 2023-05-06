<?php

namespace App\Tests\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DataControllerTest extends WebTestCase
{
    public function testDataIndex(): void
    {
        $client = static::createClient();
        $client->request('GET', '/data/index');

        $this->assertResponseIsSuccessful();
        $response = $client->getResponse();
        $content = $response->getContent();

        $data = json_decode($content, true);

        $this->assertArrayHasKey('data', $data);
        $this->assertCount(2, $data['data']);
    }

    public function testDataExpertTop(): void
    {
        $client = static::createClient();
        $client->request('GET', '/data/expert/top');

        $this->assertResponseIsSuccessful();
        $response = $client->getResponse();
        $content = $response->getContent();

        $data = json_decode($content, true);

        $this->assertArrayHasKey('data', $data);
        $this->assertCount(17, $data['data']);
    }

    public function testDataExpert(): void
    {
        $client = static::createClient();
        $client->request('GET', '/data/expert');

        $this->assertResponseIsSuccessful();
        $response = $client->getResponse();
        $content = $response->getContent();

        $data = json_decode($content, true);

        $this->assertArrayHasKey('data', $data);
    }

    public function testDataAdmin(): void
    {
        $client = static::createClient();
        $client->request('GET', '/data/admin');

        $this->assertResponseIsSuccessful();
        $response = $client->getResponse();
        $content = $response->getContent();

        $data = json_decode($content, true);

        $this->assertArrayHasKey('data', $data);
    }

    public function testTopReportList(): void
    {
        $client = static::createClient();
        $client->request('GET', '/report/list');

        $this->assertResponseIsSuccessful();
        $response = $client->getResponse();
        $content = $response->getContent();

        $data = json_decode($content, true);

        $this->assertIsArray($data);
    }

    public function testTopReportLinks(): void
    {
        $client = static::createClient();
        $client->request('GET', '/report/links/2020-11-27');

        $this->assertResponseIsSuccessful();
        $response = $client->getResponse();
        $content = $response->getContent();

        $data = json_decode($content, true);

        $this->assertIsArray($data);
    }
}
