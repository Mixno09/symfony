<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\ValueObject\Asset;
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
    private $id;
    /**
     * @ORM\Column(type="product_title", length=255)
     * @var \App\Entity\ValueObject\Product\Title
     */
    private $title;
    /**
     * @ORM\Column(type="text")
     * @var string
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
     * @param string $description
     * @param \App\Entity\ValueObject\Asset $image
     */
    public function __construct(Title $title, string $description, Asset $image)
    {
        $this->title = $title;
        $this->description = $description;
        $this->image = $image;
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
     * @return string
     */
    public function getDescription(): string
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