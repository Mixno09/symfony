<?php

declare(strict_types=1);

namespace App\UseCase\DeleteProduct;

final class Command
{
    /**
     * @var int
     */
    public $id;

    /**
     * Command constructor.
     * @param int $id
     */
    public function __construct(int $id)
    {
        $this->id = $id;
    }
}