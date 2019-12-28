<?php

declare(strict_types=1);

namespace App\Entity;

use DomainException;
use InvalidArgumentException;
use Symfony\Component\Validator\Constraints as Assert;

class Product
{
    /**
     * @var int
     */
    public $id = 0;
    /**
     * @var string
     * @Assert\NotBlank
     * @Assert\Length(max = 255)
     */
    public $title = '';
    /**
     * @var string
     * @Assert\NotBlank
     */
    public $description = '';
    /**
     * @var string
     */
    public $image = '';
    /**
     * @var \App\Entity\Review[]
     */
    private $reviews = [];

    public function addReview(Review $review): void
    {
        $userReview = $this->getUserReview($review->author);
        if ($userReview instanceof Review) {
            throw new DomainException("Отзыв уже оставлен пользователем id = {$review->author->id}");
        }

        $this->reviews[] = $review;
    }

    /**
     * @return \App\Entity\Review[]
     */
    public function getReviews(): array
    {
        return $this->reviews;
    }

    public function getUserReview(User $user): ?Review
    {
        foreach ($this->reviews as $review) {
            if ($user->equals($review->author)) {
                return $review;
            }
        }
        return null;
    }

    public function getReview(int $id): ?Review
    {
        foreach ($this->reviews as $review) {
            if ($review->id === $id) {
                return $review;
            }
        }
        return null;
    }

    public function deleteReview(int $reviewId): void
    {
        foreach ($this->reviews as $index => $review) {
            if ($review->id === $reviewId) {
                unset($this->reviews[$index]);
                return;
            }
        }
        throw new InvalidArgumentException("Не удалось удалить отзыв с id = {$reviewId} у продукта с id = {$this->id}");
    }
}