<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\ValueObject\Asset;
use App\Entity\ValueObject\ProductDescription;
use App\Entity\ValueObject\ProductTitle;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="products")
 */
final class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     * @var int
     */
    private $id = 0;
    /**
     * @ORM\Embedded(class="App\Entity\ValueObject\ProductTitle")
     * @var \App\Entity\ValueObject\ProductTitle
     */
    private $title;
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
     * @param \App\Entity\ValueObject\ProductTitle $title
     * @param \App\Entity\ValueObject\ProductDescription $description
     * @param \App\Entity\ValueObject\Asset $image
     */
    public function __construct(ProductTitle $title, ProductDescription $description, Asset $image)
    {
        $this->title = $title;
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
     * @return int
     */
    public function getId(): int
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
}