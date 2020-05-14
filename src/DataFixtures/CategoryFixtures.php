<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\ValueObject\Slug;
use App\Entity\ValueObject\Title;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use InvalidArgumentException;
use Ramsey\Uuid\Uuid;

final class CategoryFixtures extends Fixture
{
    public const CATEGORY_NUMBER = 100;

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < self::CATEGORY_NUMBER; $i++) {
            $category = $this->createCategory($i);
            $manager->persist($category);
            $this->addReference(
                self::getReferenceName($i),
                $category
            );
        }
        $manager->flush();
    }

    private function createCategory(int $i): Category
    {
        $id = Uuid::uuid4();
        $title = new Title("Категория {$i}");
        $slug = new Slug("category-{$i}");

        return new Category($id, $title, $slug);
    }

    public static function getReferenceName(int $i): string
    {
        if ($i < 0 || $i >= self::CATEGORY_NUMBER) {
            throw new InvalidArgumentException("Неверный номер категории");
        }
        return (Category::class . '_' . $i);
    }
}