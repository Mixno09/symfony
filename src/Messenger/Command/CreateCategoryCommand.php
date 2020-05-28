<?php

declare(strict_types=1);

namespace App\Messenger\Command;

use App\Validator\Constraints as AppAssert;
use Symfony\Component\Validator\Constraints as Assert;

final class CreateCategoryCommand
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
}