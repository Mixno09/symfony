<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\ValueObject\ProductDescription;
use App\Entity\ValueObject\ProductSlug;
use App\Entity\ValueObject\ProductTitle;
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
        for ($i = 0; $i < 100; $i++) {
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
        $categories = [];
        $numbers = $this->faker->randomElements(
            [0, 1, 2, 3, 4, 5, 6, 7, 8, 9],
            $this->faker->numberBetween(1, 8)
        );
        foreach ($numbers as $i) {
            $category = $this->getReference(Category::class . '_' . $i);
            $categories[] = $category;
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
