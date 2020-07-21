<?php

declare(strict_types=1);

namespace App\Messenger\Command;

use App\Entity\Category;
use App\Validator\Constraints as AppAssert;
use Symfony\Component\Validator\Constraints as Assert;

final class UpdateCategoryCommand
{
    /**
     * @var string
     * @Assert\NotBlank
     * @Assert\Uuid
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

    public function populate(Category $category): void
    {
        $this->id = $category->getId()->toString();
        $this->title = (string) $category->getTitle();
        $this->slug = (string) $category->getSlug();
    }
}