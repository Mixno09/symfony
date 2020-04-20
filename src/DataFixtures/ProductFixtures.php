<?php

namespace App\DataFixtures;

use App\Entity\Product;
use App\Entity\ValueObject\ProductDescription;
use App\Entity\ValueObject\ProductSlug;
use App\Entity\ValueObject\ProductTitle;
use App\Service\AssetManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\File\File;

class ProductFixtures extends Fixture
{
    /**
     * @var \Faker\Generator
     */
    private $faker;

    /**
     * @var \App\Service\AssetManager
     */
    private $assetManager;

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
            $this->faker->realText(ProductTitle::MAX_LENGTH)
        );
        $slug = new ProductSlug(
            $this->faker->unique()->slug(1)
        );
        $description = new ProductDescription(
            $this->faker->realText(ProductDescription::MIN_LENGTH + 200)
        );
        $image = $this->assetManager->upload(
            new File(__DIR__ . '/images/product.jpeg')
        );

        return new Product($id, $title, $slug, $description, $image);
    }
}
