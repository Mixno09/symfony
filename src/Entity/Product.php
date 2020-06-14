<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\ValueObject\ProductDescription;
use App\Entity\ValueObject\ProductImage;
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
     * @ORM\Embedded(class="App\Entity\ValueObject\ProductImage")
     */
    private ProductImage $image;
    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Category")
     * @ORM\OrderBy(value={"title.value": "ASC"})
     */
    private Collection $categories;

    /**
     * Product constructor.
     * @param UuidInterface $id
     * @param \App\Entity\ValueObject\Title $title
     * @param \App\Entity\ValueObject\Slug $slug
     * @param \App\Entity\ValueObject\ProductDescription $description
     * @param \App\Entity\ValueObject\ProductImage $image
     * @param \App\Entity\Category[] $categories
     */
    public function __construct(
        UuidInterface $id,
        Title $title,
        Slug $slug,
        ProductDescription $description,
        ProductImage $image,
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
     * @param \App\Entity\Category[] $categories
     * @param \App\Entity\ValueObject\ProductDescription $description
     * @param \App\Entity\ValueObject\ProductImage $image
     */
    public function update(Title $title, array $categories, ProductDescription $description, ProductImage $image = null): void
    {
        $this->title = $title;
        $this->setCategories(...$categories);
        $this->description = $description;

        if ($image instanceof ProductImage) {
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
        $collection = new ArrayCollection();
        foreach ($categories as $category) {
            $key = $category->getId()->toString();
            if ($collection->containsKey($key)) {
                throw new InvalidArgumentException('Аргумент $categories должен содержать только уникальные Category');
            }
            $collection->set($key, $category);
        }
        $this->categories = $collection;
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
     * @return \App\Entity\Category[]
     */
    public function getCategories(): array
    {
        return $this->categories->toArray();
    }

    /**
     * @return \App\Entity\ValueObject\ProductImage
     */
    public function getImage(): ProductImage
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