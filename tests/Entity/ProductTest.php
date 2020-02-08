<?php

namespace App\Tests\Entity;

use App\Entity\ImageInterface;
use App\Entity\Product;
use PHPUnit\Framework\TestCase;

class ProductTest extends TestCase
{
    public function testAccessors()
    {
        $titleExpected = 'title';
        $descriptionExpected = 'desc';
        /** @var \App\Service\Image $imageExpected */
        $imageExpected = $this->createMock(ImageInterface::class);

        $product = new Product($titleExpected, $descriptionExpected, $imageExpected);
        $titleActual = $product->getTitle();
        $descriptionActual = $product->getDescription();
        $imageActual = $product->getImage();

        $this->assertEquals($titleExpected, $titleActual);
        $this->assertEquals($descriptionExpected, $descriptionActual);
        $this->assertEquals($imageExpected, $imageActual);
    }
}