<?php

namespace App\Tests\Entity;

use App\Entity\ValueObject\Asset;
use App\Entity\Product;
use PHPUnit\Framework\TestCase;

class ProductTest extends TestCase
{
    public function testAccessors()
    {
        $titleExpected = 'title';
        $descriptionExpected = 'desc';
        /** @var \App\Entity\ValueObject\Asset $imageExpected */
        $imageExpected = $this->createMock(Asset::class);

        $product = new Product($titleExpected, $descriptionExpected, $imageExpected);
        $titleActual = $product->getTitle();
        $descriptionActual = $product->getDescription();
        $imageActual = $product->getImage();

        $this->assertEquals($titleExpected, $titleActual);
        $this->assertEquals($descriptionExpected, $descriptionActual);
        $this->assertEquals($imageExpected, $imageActual);
    }
}