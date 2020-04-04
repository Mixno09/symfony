<?php

namespace App\DataFixtures;

use App\Entity\Product;
use App\Entity\ValueObject\ProductDescription;
use App\Entity\ValueObject\ProductTitle;
use App\Service\AssetManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
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
        $title = new ProductTitle(
            $this->faker->realText(
                $this->faker->numberBetween(ProductTitle::MIN_LENGTH + 10, ProductTitle::MAX_LENGTH)
            )
        );
        $description = new ProductDescription(
            $this->faker->realText(
                $this->faker->numberBetween(ProductDescription::MIN_LENGTH + 10, ProductDescription::MIN_LENGTH + 500)
            )
        );
        $image = $this->assetManager->upload(
            new File(__DIR__ . '/images/product.jpeg')
        );

        return new Product($title, $description, $image);
    }
}
