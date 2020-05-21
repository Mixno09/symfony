<?php

declare(strict_types=1);

namespace App\UseCase\CreateCategory;

use App\Validator\Constraints as AppAssert;
use Symfony\Component\Validator\Constraints as Assert;

final class Command
{
    /**
     * @var string
     * @Assert\NotBlank
     * @Assert\Uuid
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