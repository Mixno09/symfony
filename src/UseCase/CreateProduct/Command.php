<?php

declare(strict_types=1);

namespace App\UseCase\CreateProduct;

use App\Validator\Constraints as AppAssert;
use Symfony\Component\Validator\Constraints as Assert;

final class Command
{
    /**
     * @var string
     * @Assert\NotBlank
     * @Assert\Uuid
     * @AppAssert\NotExistsProduct
     */
    public $id;
    /**
     * @var string
     * @Assert\NotBlank
     * @AppAssert\Title
     */
    public $title;
    /**
     * @var string
     * @Assert\NotBlank
     * @AppAssert\Slug
     */
    public $slug;
    /**
     * @var string
     * @Assert\NotBlank
     * @AppAssert\ProductDescription
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