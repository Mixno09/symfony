<?php

declare(strict_types=1);

namespace App\Messenger\Command;

use App\Entity\Category;
use App\Entity\Product;
use App\Validator\Constraints as AppAssert;
use Symfony\Component\Validator\Constraints as Assert;

final class UpdateProductCommand
{
    /**
     * @var string
     * @Assert\Uuid
     * @Assert\NotBlank
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
     * @var string[]
     * @Assert\NotBlank
     * @Assert\Unique
     * @Assert\All({
     *     @Assert\NotBlank,
     *     @AppAssert\ExistsCategory
     * })
     */
    public $categories;
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
        $this->categories = array_map(
            fn(Category $category): string => $category->getId()->toString(),
            $product->getCategories()
        );
        $this->description = (string) $product->getDescription();
    }
}