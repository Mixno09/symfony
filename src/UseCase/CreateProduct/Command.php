<?php

declare(strict_types=1);

namespace App\UseCase\CreateProduct;

use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\ValueObject\ProductTitle;
use App\Entity\ValueObject\ProductDescription;

final class Command
{
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
     * @var \Symfony\Component\HttpFoundation\File\UploadedFile
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