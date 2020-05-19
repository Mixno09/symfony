<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\ValueObject\Asset;
use App\Entity\ValueObject\ProductDescription;
use App\Entity\ValueObject\Slug;
use App\Entity\ValueObject\Title;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
     * @ORM\Embedded(class="App\Entity\ValueObject\Title")
     */
    private Title $title;
    /**
     * @ORM\Embedded(class="App\Entity\ValueObject\Slug")
     */
    private Slug $slug;
    /**
     * @ORM\Embedded(class="App\Entity\ValueObject\ProductDescription")
     */
    private ProductDescription $description;
    /**
     * @ORM\Embedded(class="App\Entity\ValueObject\Asset")
     */
    private Asset $image;
    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Category")
     */
    private Collection $categories;

    /**
     * Product constructor.
     * @param UuidInterface $id
     * @param \App\Entity\ValueObject\Title $title
     * @param \App\Entity\ValueObject\Slug $slug
     * @param \App\Entity\ValueObject\ProductDescription $description
     * @param \App\Entity\ValueObject\Asset $image
     * @param \App\Entity\Category[] $categories
     */
    public function __construct(
        UuidInterface $id,
        Title $title,
        Slug $slug,
        ProductDescription $description,
        Asset $image,
        array $categories
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->slug = $slug;
        $this->description = $description;
        $this->image = $image;
        $this->categories = new ArrayCollection();
        $this->setCategories(...$categories);
    }

    /**
     * @param \App\Entity\ValueObject\Title $title
     * @param \App\Entity\ValueObject\ProductDescription $description
     * @param \App\Entity\ValueObject\Asset|null $image
     */
    public function update(Title $title, ProductDescription $description, Asset $image = null): void
    {
        $this->title = $title;
        $this->description = $description;

        if ($image instanceof Asset) {
            $this->image = $image;
        }
    }

    /**
     * @param \App\Entity\Category ...$categories
     */
    private function setCategories(Category ...$categories): void
    {
        if (count($categories) === 0) {
            throw new InvalidArgumentException('Аргумент $categories не должен быть пустым');
        }
        foreach ($categories as $category) {
            $key = $category->getId()->toString();
            if ($this->categories->containsKey($key)) {
                throw new InvalidArgumentException('Аргумент $categories должен содержать только уникальные Category');
            }
            $this->categories->set($key, $category);
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
     * @return \App\Entity\ValueObject\Title
     */
    public function getTitle(): Title
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
     * @return \App\Entity\ValueObject\Slug
     */
    public function getSlug(): Slug
    {
        return $this->slug;
    }
}