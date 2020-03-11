<?php

namespace App\Tests\Entity;

use App\Entity\Product;
use App\Entity\ValueObject\Asset;
use App\Entity\ValueObject\ProductDescription;
use App\Entity\ValueObject\ProductTitle;
use PHPUnit\Framework\TestCase;

class ProductTest extends TestCase
{
    public function testCreateProduct()
    {
        $title = new ProductTitle('title');
        $description = new ProductDescription('description');
        $image = new Asset('image.jpg');

        $product = new Product($title, $description, $image);

        $this->assertSame($title, $product->getTitle());
        $this->assertSame($description, $product->getDescription());
        $this->assertSame($image, $product->getImage());
    }

    public function testUpdateProduct()
    {
        $image = new Asset('image.jpg');
        $product = new Product(
            new ProductTitle('title'),
            new ProductDescription('description'),
            $image
        );
        $title = new ProductTitle('title');
        $description = new ProductDescription('description');

        $product->update($title, $description);

        $this->assertSame($title, $product->getTitle());
        $this->assertSame($description, $product->getDescription());
        $this->assertSame($image, $product->getImage());

        $image = new Asset('image.jpg');
        $product->update($title, $description, $image);

        $this->assertSame($image, $product->getImage());
    }
}