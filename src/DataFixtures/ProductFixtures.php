<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\ValueObject\CategorySlug;
use App\Entity\ValueObject\CategoryTitle;
use App\Entity\ValueObject\ProductDescription;
use App\Entity\ValueObject\ProductSlug;
use App\Entity\ValueObject\ProductTitle;
use App\Service\AssetManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\File\File;

class ProductFixtures extends Fixture
{
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
        for ($i = 0; $i < 90; $i++) {
            $product = $this->createProduct();
            $manager->persist($product);
        }
        $manager->flush();
    }

    private function createProduct(): Product
    {
        $id = Uuid::uuid4();
        $title = new ProductTitle(
            $this->faker->realText(255)
        );
        $slug = new ProductSlug(
            $this->faker->unique()->slug(1)
        );
        $description = new ProductDescription(
            $this->faker->realText(205)
        );
        $image = $this->assetManager->upload(
            new File(__DIR__ . '/images/product.jpeg')
        );
        $category = new Category(
            $id,
            new CategoryTitle(
                $this->faker->realText(255)
            ),
            new CategorySlug(
                $this->faker->unique()->slug(1))
        );

        return new Product($id, $title, $slug, $description, $image, [$category]);
    }
}
