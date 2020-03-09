<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\ValueObject\Asset;
use App\Entity\ValueObject\Product\Description;
use App\Entity\ValueObject\Product\Title;
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
     * @ORM\Column(type="product_title", length=255)
     * @var \App\Entity\ValueObject\Product\Title
     */
    private $title;
    /**
     * @ORM\Column(type="product_description")
     * @var \App\Entity\ValueObject\Product\Description
     */
    private $description;
    /**
     * @ORM\Column(type="asset")
     * @var \App\Entity\ValueObject\Asset
     */
    private $image;

    /**
     * Product constructor.
     * @param \App\Entity\ValueObject\Product\Title $title
     * @param \App\Entity\ValueObject\Product\Description $description
     * @param \App\Entity\ValueObject\Asset $image
     */
    public function __construct(Title $title, Description $description, Asset $image)
    {
        $this->title = $title;
        $this->description = $description;
        $this->image = $image;
    }

    public function update(Title $title, Description $description, Asset $image = null): void
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
     * @return \App\Entity\ValueObject\Product\Title
     */
    public function getTitle(): Title
    {
        return $this->title;
    }

    /**
     * @return \App\Entity\ValueObject\Product\Description
     */
    public function getDescription(): Description
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