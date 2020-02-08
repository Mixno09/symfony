<?php

declare(strict_types=1);

namespace App\Entity;

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
     * @ORM\Column(type="string", length=255)
     * @var string
     */
    private $title;
    /**
     * @ORM\Column(type="text")
     * @var string
     */
    private $description;
    /**
     * @var \App\Entity\ImageInterface
     */
    private $image;

    /**
     * Product constructor.
     * @param string $title
     * @param string $description
     * @param \App\Entity\ImageInterface $image
     */
    public function __construct(string $title, string $description, ImageInterface $image)
    {
        $this->title = $title;
        $this->description = $description;
        $this->image = $image;
    }

    /**
     * @return string
     */
    public function getTitle(): string
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
     * @return \App\Entity\ImageInterface
     */
    public function getImage(): ImageInterface
    {
        return $this->image;
    }
}