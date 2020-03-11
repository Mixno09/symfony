<?php

declare(strict_types=1);

namespace App\UseCase\Product\CreateProduct;

use Symfony\Component\Validator\Constraints as Assert;

final class Command
{
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