<?php

declare(strict_types=1);

namespace App\Fixture;

use App\Entity\Review;
use App\Repository\ProductRepositoryInterface;
use App\Repository\UserRepositoryInterface;
use DateTimeImmutable;
use Faker\Factory;
use Sylius\Bundle\FixturesBundle\Fixture\AbstractFixture;

class ReviewFixture extends AbstractFixture
{
    /**
     * @var \App\Repository\ProductRepositoryInterface
     */
    private $productRepository;
    /**
     * @var \App\Repository\UserRepositoryInterface
     */
    private $userRepository;

    /**
     * ReviewFixture constructor.
     * @param \App\Repository\ProductRepositoryInterface $productRepository
     * @param \App\Repository\UserRepositoryInterface $userRepository
     */
    public function __construct(ProductRepositoryInterface $productRepository, UserRepositoryInterface $userRepository)
    {
        $this->productRepository = $productRepository;
        $this->userRepository = $userRepository;
    }

    public function load(array $options): void
    {
        /** @var \App\Entity\User[] $users */
        $users = $this->userRepository->paginate(1, 11 )->getItems();

        /** @var \App\Entity\Product[] $products */
        $products = $this->productRepository->paginate(1, 20)->getItems();

        $faker = Factory::create('ru_RU');
        foreach ($products as $product) {
            shuffle($users);
            $authors = array_slice($users, rand(0, 10));
            foreach ($authors as $author) {
                $text = $faker->realText(50);
                $review = new Review($author, $text);
                $dateTime = $faker->dateTime;
                $review->date = DateTimeImmutable::createFromMutable($dateTime);
                $product->addReview($review);
            }
            $this->productRepository->save($product);
        }
    }

    public function getName(): string
    {
        return 'review';
    }
}