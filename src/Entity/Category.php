<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\ValueObject\Slug;
use App\Entity\ValueObject\Title;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;

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
     * @ORM\Embedded(class="App\Entity\ValueObject\Title")
     */
    private Title $title;
    /**
     * @ORM\Embedded(class="App\Entity\ValueObject\Slug")
     */
    private Slug $slug;

    /**
     * Category constructor.
     * @param \Ramsey\Uuid\UuidInterface $id
     * @param \App\Entity\ValueObject\Title $title
     * @param \App\Entity\ValueObject\Slug $slug
     */
    public function __construct(UuidInterface $id, Title $title, Slug $slug)
    {
        $this->id = $id;
        $this->title = $title;
        $this->slug = $slug;
    }

    /**
     * @return \Ramsey\Uuid\UuidInterface
     */
    public function getId(): UuidInterface
    {
        return $this->id;
    }

    /**
     * @return \App\Entity\ValueObject\Title
     */
    public function getTitle(): Title
    {
        return $this->title;
    }

    /**
     * @return \App\Entity\ValueObject\Slug
     */
    public function getSlug(): Slug
    {
        return $this->slug;
    }
}