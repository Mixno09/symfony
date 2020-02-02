<?php

declare(strict_types=1);

namespace App\Entity;

use App\Service\ImageInterface;

final class Product
{
    /**
     * @var string
     */
    private $title;
    /**
     * @var string
     */
    private $description;
    /**
     * @var \App\Service\ImageInterface
     */
    private $image;

    /**
     * Product constructor.
     * @param string $title
     * @param string $description
     * @param \App\Service\ImageInterface $image
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
     * @return \App\Service\ImageInterface
     */
    public function getImage(): ImageInterface
    {
        return $this->image;
    }
}