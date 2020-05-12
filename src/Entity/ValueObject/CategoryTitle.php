<?php

declare(strict_types=1);

namespace App\Entity\ValueObject;

use InvalidArgumentException;

final class CategoryTitle
{
    private string $value;

    /**
     * CategoryTitle constructor.
     * @param string $value
     */
    public function __construct(string $value)
    {
        if ($value === '') {
            throw new InvalidArgumentException('Значение не должно быть пустым');
        }
        $this->value = $value;
    }
}