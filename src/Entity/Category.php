<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\ValueObject\CategorySlug;
use App\Entity\ValueObject\CategoryTitle;
use Ramsey\Uuid\UuidInterface;

final class Category
{
    private UuidInterface $id;
    private CategoryTitle $title;
    private CategorySlug $slug;

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