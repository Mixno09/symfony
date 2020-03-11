<?php

declare(strict_types=1);

namespace App\UseCase\Product\UpdateProduct;

use App\Entity\Product;
use Symfony\Component\Validator\Constraints as Assert;

final class Command
{
    /**
     * @var int
     */
    public $id;
    /**
     * @var string
     * @Assert\NotBlank
     * @Assert\Length(min = Title::MIN_LENGTH, max = Title::MAX_LENGTH)
     */
    public $title;
    /**
     * @var string
     * @Assert\NotBlank
     * @Assert\Length(min = 5)
     */
    public $description;
    /**
     * @var \Symfony\Component\HttpFoundation\File\UploadedFile|null
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