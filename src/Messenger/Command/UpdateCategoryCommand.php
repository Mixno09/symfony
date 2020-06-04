<?php

declare(strict_types=1);

namespace App\Messenger\Command;

final class UpdateCategoryCommand
{
    /**
     * @var string
     */
    public $id;
    /**
     * @var string
     */
    public $title;
    /**
     * @var string
     */
    public $slug;
}