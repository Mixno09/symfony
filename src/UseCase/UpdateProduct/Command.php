<?php

declare(strict_types=1);

namespace App\UseCase\UpdateProduct;

use App\Entity\Product;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\ValueObject\ProductTitle;
use App\Entity\ValueObject\ProductDescription;

final class Command
{
    /**
     * @var int
     */
    public $id;
    /**
     * @var string
     * @Assert\NotBlank
     * @Assert\Length(min = ProductTitle::MIN_LENGTH, max = ProductTitle::MAX_LENGTH)
     */
    public $title;
    /**
     * @var string
     * @Assert\NotBlank
     * @Assert\Length(min = ProductDescription::MIN_LENGTH)
     */
    public $description;
    /**
     * @var \Symfony\Component\HttpFoundation\File\File|null
     * @Assert\Image(
     *     minWidth = 400,
     *     maxWidth = 800,
     *     minHeight = 400,
     *     maxHeight = 800,
     *     minRatio = 1,
     *     maxRatio = 1,
     *     mimeTypes = "image/jpeg"
     * )
     */
    public $image;

    /**
     * @param \App\Entity\Product $product
     */
    public function populate(Product $product): void
    {
        $this->id = $product->getId();
        $this->title = (string) $product->getTitle();
        $this->description = (string) $product->getDescription();
    }
}