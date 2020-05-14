<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\ValueObject\CategorySlug;
use App\Entity\ValueObject\CategoryTitle;
use Ramsey\Uuid\UuidInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="categories")
 */
class Category
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(type="uuid")
     */
    private UuidInterface $id;
    /**
     * @ORM\Embedded(class="App\Entity\ValueObject\CategoryTitle")
     */
    private CategoryTitle $title;
    /**
     * @ORM\Embedded(class="App\Entity\ValueObject\CategorySlug")
     */
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