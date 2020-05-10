<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\ValueObject\CategorySlug;
use App\Entity\ValueObject\CategoryTitle;
use Ramsey\Uuid\UuidInterface;

final class Category
{
    /**
     * @var \Ramsey\Uuid\UuidInterface
     */
    private $id;
    /**
     * @var \App\Entity\ValueObject\CategoryTitle
     */
    private $title;
    /**
     * @var \App\Entity\ValueObject\CategorySlug
     */
    private $slug;

    /**
     * Category constructor.
     * @param \Ramsey\Uuid\UuidInterface $id
     * @param \App\Entity\ValueObject\CategoryTitle $title
     * @param \App\Entity\ValueObject\CategorySlug $slug
     */
    public function __construct(UuidInterface $id, CategoryTitle $title, CategorySlug $slug)
    {
        $this->id = $id;
        $this->title = $title;
        $this->slug = $slug;
    }
}