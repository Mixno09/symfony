<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\ValueObject\Asset;
use App\Entity\ValueObject\ProductDescription;
use App\Entity\ValueObject\ProductSlug;
use App\Entity\ValueObject\ProductTitle;
use Doctrine\ORM\Mapping as ORM;
use InvalidArgumentException;
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
     */
    private UuidInterface $id;
    /**
     * @ORM\Embedded(class="App\Entity\ValueObject\ProductTitle")
     */
    private ProductTitle $title;
    /**
     * @ORM\Embedded(class="App\Entity\ValueObject\ProductSlug")
     */
    private ProductSlug $slug;
    /**
     * @ORM\Embedded(class="App\Entity\ValueObject\ProductDescription")
     */
    private ProductDescription $description;
    /**
     * @ORM\Embedded(class="App\Entity\ValueObject\Asset")
     */
    private Asset $image;
    /**
     * @var \App\Entity\Category[]
     */
    private array $categories;

    /**
     * Product constructor.
     * @param UuidInterface $id
     * @param \App\Entity\ValueObject\ProductTitle $title
     * @param \App\Entity\ValueObject\ProductSlug $slug
     * @param \App\Entity\ValueObject\ProductDescription $description
     * @param \App\Entity\ValueObject\Asset $image
     * @param \App\Entity\Category[] $categories
     */
    public function __construct(
        UuidInterface $id,
        ProductTitle $title,
        ProductSlug $slug,
        ProductDescription $description,
        Asset $image,
        array $categories
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->slug = $slug;
        $this->description = $description;
        $this->image = $image;

        if (count($categories) === 0) {
            throw new InvalidArgumentException('Аргумент $categories не должен быть пустым');
        }
        foreach ($categories as $category) {
            if (! $category instanceof Category) {
                throw new InvalidArgumentException('Аргумент $categories должен содержать только Category');
            }
        }
        if (count($categories) !== count(array_unique($categories, SORT_REGULAR))) {
            throw new InvalidArgumentException('Аргумент $categories должен содержать только уникальные Category');
        }
        $this->categories = $categories;
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