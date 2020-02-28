<?php

namespace App\Tests\Controller;

use App\DataFixtures\ShowProductTestFixtures;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Liip\TestFixturesBundle\Test\FixturesTrait;

class ProductControllerTest extends WebTestCase
{
    use FixturesTrait;

    public function testProductNotFound()
    {
        $client = $this->createClient();

        $client->request('GET', '/product/1');

        $this->assertStatusCode(404, $client);
    }

    public function testShowProduct()
    {
        $this->loadFixtures([ShowProductTestFixtures::class]);
        $client = $this->createClient();

        $crawler = $client->request('GET', '/product/1');

        $this->assertStatusCode(200, $client);
        $this->assertSelectorTextSame('main h1.text-center.mb-5', 'title');
        $this->assertSelectorTextSame('main p', 'description');
        $image = $crawler->filter('main img.img-thumbnail.mb-3')->attr('src');
        $this->assertSame('/image.jpg', $image);
    }
}