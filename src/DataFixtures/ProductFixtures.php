<?php

namespace App\DataFixtures;

use App\Entity\Product;
use App\Entity\ValueObject\ProductDescription;
use App\Entity\ValueObject\ProductImage;
use App\Entity\ValueObject\Slug;
use App\Entity\ValueObject\Title;
use App\Service\FileManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\File\File;

class ProductFixtures extends Fixture implements DependentFixtureInterface
{
    private const PRODUCT_NUMBER = 1000;

    private Generator $faker;
    private FileManager $fileManager;

    /**
     * ProductFixtures constructor.
     * @param \App\Service\FileManager $fileManager
     */
    public function __construct(FileManager $fileManager)
    {
        $this->faker = Factory::create();
        $this->fileManager = $fileManager;
    }

    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < self::PRODUCT_NUMBER; $i++) {
            $product = $this->createProduct($i);
            $manager->persist($product);
        }
        $manager->flush();
    }

    private function createProduct(int $i): Product
    {
        $id = Uuid::uuid4();
        $title = new Title("Продукт {$i}");
        $slug = new Slug("product-{$i}");
        $description = new ProductDescription("Описание продукта {$i}");
        $image = ProductImage::create(
            new File(__DIR__ . '/images/product.jpeg'),
            $this->fileManager
        );
        $categories = [];
        $categories[] = $this->getReference(
            CategoryFixtures::getReferenceName($i % CategoryFixtures::CATEGORY_NUMBER)
        );
        if ($i % 2 === 0) {
            $categories[] = $this->getReference(
                CategoryFixtures::getReferenceName(($i + 1) % CategoryFixtures::CATEGORY_NUMBER)
            );
        }
        if ($i % 3 === 0) {
            $categories[] = $this->getReference(
                CategoryFixtures::getReferenceName(($i + 2) % CategoryFixtures::CATEGORY_NUMBER)
            );
        }
        return new Product($id, $title, $slug, $description, $image, $categories);
    }

    /**
     * @inheritDoc
     */
    public function getDependencies()
    {
        return [
            CategoryFixtures::class,
        ];
    }
}
