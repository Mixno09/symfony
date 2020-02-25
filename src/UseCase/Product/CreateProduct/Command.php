<?php

declare(strict_types=1);

namespace App\UseCase\Product\CreateProduct;

use Symfony\Component\HttpFoundation\File\UploadedFile;

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
     * @var \Symfony\Component\HttpFoundation\File\UploadedFile
     */
    private $image;

    /**
     * Command constructor.
     * @param string $title
     * @param string $description
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $image
     */
    public function __construct(string $title, string $description, UploadedFile $image)
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
     * @return \Symfony\Component\HttpFoundation\File\UploadedFile
     */
    public function getImage(): UploadedFile
    {
        return $this->image;
    }
}