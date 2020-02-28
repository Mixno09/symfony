<?php

namespace App\DataFixtures;

use App\DataFixtures\ValueObject\AssetFactory;
use App\Entity\Product;
use App\Entity\ValueObject\Product\Description;
use App\Entity\ValueObject\Product\Title;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ShowProductTestFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $product = new Product(
            Title::fromString('title'),
            Description::fromString('description'),
            AssetFactory::newAsset()
        );
        $manager->persist($product);
        $manager->flush();
    }
}
