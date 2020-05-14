<?php

declare(strict_types=1);

namespace App\Tests\Entity;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\ValueObject\Asset;
use App\Entity\ValueObject\ProductDescription;
use App\Entity\ValueObject\Slug;
use App\Entity\ValueObject\Title;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class ProductTest extends TestCase
{
    public function testCreateProduct()
    {
        $id = Uuid::uuid4();
        $title = new Title('title');
        $slug = new Slug('slug');
        $description = new ProductDescription('description');
        $image = new Asset('image.jpg');
        $categories = [
            new Category(
                Uuid::uuid4(),
                new Title('title'),
                new Slug('slug')
            ),
            new Category(
                Uuid::uuid4(),
                new Title('title'),
                new Slug('slug')
            ),
        ];

        $product = new Product($id, $title, $slug, $description, $image, $categories);

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
            new Title('title'),
            new Slug('slug'),
            new ProductDescription('description'),
            $image,
            [
                new Category(
                    Uuid::uuid4(),
                    new Title('title'),
                    new Slug('slug')
                ),
                new Category(
                    Uuid::uuid4(),
                    new Title('title'),
                    new Slug('slug')
                ),
            ],
        );
        $title = new Title('title');
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