<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\ValueObject\Asset;
use App\Entity\ValueObject\ProductDescription;
use App\Entity\ValueObject\ProductSlug;
use App\Entity\ValueObject\ProductTitle;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="products")
 */
final class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(type="uuid")
     * @var \Ramsey\Uuid\UuidInterface
     */
    private $id;
    /**
     * @ORM\Embedded(class="App\Entity\ValueObject\ProductTitle")
     * @var \App\Entity\ValueObject\ProductTitle
     */
    private $title;
    /**
     * @ORM\Embedded(class="App\Entity\ValueObject\ProductSlug")
     * @var \App\Entity\ValueObject\ProductSlug
     */
    private $slug;
    /**
     * @ORM\Embedded(class="App\Entity\ValueObject\ProductDescription")
     * @var \App\Entity\ValueObject\ProductDescription
     */
    private $description;
    /**
     * @ORM\Embedded(class="App\Entity\ValueObject\Asset")
     * @var \App\Entity\ValueObject\Asset
     */
    private $image;

    /**
     * Product constructor.
     * @param UuidInterface $id
     * @param \App\Entity\ValueObject\ProductTitle $title
     * @param \App\Entity\ValueObject\ProductSlug $slug
     * @param \App\Entity\ValueObject\Asset $image
     * @param \App\Entity\ValueObject\ProductDescription $description
     */
    public function __construct(
        UuidInterface $id,
        ProductTitle $title,
        ProductSlug $slug,
        ProductDescription $description,
        Asset $image
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->slug = $slug;
        $this->description = $description;
        $this->image = $image;
    }

    /**
     * @param \App\Entity\ValueObject\ProductTitle $title
     * @param \App\Entity\ValueObject\ProductDescription $description
     * @param \App\Entity\ValueObject\Asset|null $image
     */
    public function update(ProductTitle $title, ProductDescription $description, Asset $image = null): void
    {
        $this->title = $title;
        $this->description = $description;

        if ($image instanceof Asset) {
            $this->image = $image;
        }
    }

    /**
     * @return UuidInterface
     */
    public function getId(): UuidInterface
    {
        return $this->id;
    }

    /**
     * @return \App\Entity\ValueObject\ProductTitle
     */
    public function getTitle(): ProductTitle
    {
        return $this->title;
    }

    /**
     * @return \App\Entity\ValueObject\ProductDescription
     */
    public function getDescription(): ProductDescription
    {
        return $this->description;
    }

    /**
     * @return \App\Entity\ValueObject\Asset
     */
    public function getImage(): Asset
    {
        return $this->image;
    }

    /**
     * @return \App\Entity\ValueObject\ProductSlug
     */
    public function getSlug(): ProductSlug
    {
        return $this->slug;
    }
}