<?php

namespace App\Tests\Controller;

use App\DataFixtures\ShowProductTestFixtures;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Liip\TestFixturesBundle\Test\FixturesTrait;

class ProductControllerTest extends WebTestCase
{
    use FixturesTrait;

    public function testShowProduct()
    {
        $this->loadFixtures([ShowProductTestFixtures::class]);
        $client = $this->createClient();

        $client->request('GET', '/product/1');
        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->markTestIncomplete(); // не законченные тесты
    }
}