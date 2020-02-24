<?php

declare(strict_types=1);

namespace App\UseCase\Product\CreateProduct;

use App\Entity\ValueObject\Asset;

final class Command
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
     * @var \App\Entity\ValueObject\Asset
     */
    private $image;

    /**
     * Command constructor.
     * @param string $title
     * @param string $description
     * @param \App\Entity\ValueObject\Asset $image
     */
    public function __construct(string $title, string $description, Asset $image)
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
     * @return \App\Entity\ValueObject\Asset
     */
    public function getImage(): Asset
    {
        return $this->image;
    }
}