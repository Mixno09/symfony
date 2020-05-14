<?php

namespace App\DataFixtures;

use App\Entity\Product;
use App\Entity\ValueObject\ProductDescription;
use App\Entity\ValueObject\Slug;
use App\Entity\ValueObject\Title;
use App\Service\AssetManager;
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
    private AssetManager $assetManager;

    /**
     * ProductFixtures constructor.
     * @param \App\Service\AssetManager $assetManager
     */
    public function __construct(AssetManager $assetManager)
    {
        $this->faker = Factory::create();
        $this->assetManager = $assetManager;
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
        $image = $this->assetManager->upload(
            new File(__DIR__ . '/images/product.jpeg')
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
