<?php

declare(strict_types=1);

namespace App\UseCase\CreateProduct;

use App\Entity\ValueObject\ProductDescription;
use App\Entity\ValueObject\ProductTitle;
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
     * @Assert\Length(min = ProductTitle::MIN_LENGTH, max = ProductTitle::MAX_LENGTH)
     */
    public $title;
    /**
     * @var string
     */
    public $slug;
    /**
     * @var string
     * @Assert\NotBlank
     * @Assert\Length(min = ProductDescription::MIN_LENGTH)
     */
    public $description;
    /**
     * @var \Symfony\Component\HttpFoundation\File\File
     * @Assert\NotBlank
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
}