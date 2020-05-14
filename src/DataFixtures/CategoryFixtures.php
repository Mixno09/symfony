<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\ValueObject\CategorySlug;
use App\Entity\ValueObject\CategoryTitle;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Ramsey\Uuid\Uuid;

final class CategoryFixtures extends Fixture
{
    private Generator $faker;

    /**
     * CategoryFixtures constructor.
     */
    public function __construct()
    {
        $this->faker = Factory::create();
    }

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 10; $i++) {
            $category = $this->createCategory();
            $manager->persist($category);
            $this->addReference(Category::class . '_' . $i, $category);
        }
        $manager->flush();
    }

    private function createCategory(): Category
    {
        $id = Uuid::uuid4();
        $title = new CategoryTitle(
            $this->faker->realText(255)
        );
        $slug = new CategorySlug(
            $this->faker->unique()->slug(1)
        );

        return new Category($id, $title, $slug);
    }
}