<?php

namespace App\Tests\Entity;

use App\Entity\Product;
use App\DataFixtures\ValueObject\AssetFactory;
use App\Entity\ValueObject\Product\Description;
use App\Entity\ValueObject\Product\Title;
use PHPUnit\Framework\TestCase;

class ProductTest extends TestCase
{
    public function testAccessors()
    {
        $titleExpected = Title::fromString('title');
        $descriptionExpected = Description::fromString('description');
        $imageExpected = AssetFactory::newAsset();

        $product = new Product($titleExpected, $descriptionExpected, $imageExpected);
        $titleActual = $product->getTitle();
        $descriptionActual = $product->getDescription();
        $imageActual = $product->getImage();

        $this->assertSame($titleExpected, $titleActual);
        $this->assertSame($descriptionExpected, $descriptionActual);
        $this->assertSame($imageExpected, $imageActual);
    }
}