<?php

namespace App\DataFixtures;

use App\DataFixtures\ValueObject\AssetFactory;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ShowProductTestFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $product = new Product('product', 'desc', AssetFactory::newAsset());
        $manager->persist($product);
        $manager->flush();
    }
}
