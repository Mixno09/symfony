<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProductControllerTest extends WebTestCase
{
    public function testShowProduct()
    {
        $client = static::createClient();

        $client->request('GET', '/product/1');
        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
    }
}