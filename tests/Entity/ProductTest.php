<?php

namespace App\Tests\Entity;

use App\Entity\Product;
use App\Entity\ValueObject\Asset;
use App\Entity\ValueObject\ProductDescription;
use App\Entity\ValueObject\ProductSlug;
use App\Entity\ValueObject\ProductTitle;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class ProductTest extends TestCase
{
    public function testCreateProduct()
    {
        $id = Uuid::uuid4();
        $title = new ProductTitle('title');
        $slug = new ProductSlug('slug');
        $description = new ProductDescription('description');
        $image = new Asset('image.jpg');

        $product = new Product($id, $title, $slug, $description, $image);

        $this->assertSame($id, $product->getId());
        $this->assertSame($title, $product->getTitle());
        $this->assertSame($slug, $product->getSlug());
        $this->assertSame($description, $product->getDescription());
        $this->assertSame($image, $product->getImage());
    }

    public function testUpdateProduct()
    {
        $image = new Asset('image.jpg');
        $product = new Product(
            Uuid::uuid4(),
            new ProductTitle('title'),
            new ProductSlug('slug'),
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