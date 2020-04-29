<?php

declare(strict_types=1);

namespace App\UseCase\UpdateProduct;

use App\Entity\Product;
use App\Validator\Constraints as AppAssert;
use Symfony\Component\Validator\Constraints as Assert;

final class Command
{
    /**
     * @var string
     */
    public $id;
    /**
     * @var string
     * @Assert\NotBlank
     * @AppAssert\ProductTitle
     */
    public $title;
    /**
     * @var string
     * @Assert\NotBlank
     * @AppAssert\ProductDescription
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
        $this->id = $product->getId()->toString();
        $this->title = (string) $product->getTitle();
        $this->slug = (string) $product->getSlug();
        $this->description = (string) $product->getDescription();
    }
}