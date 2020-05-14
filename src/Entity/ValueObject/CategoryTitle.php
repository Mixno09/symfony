<?php

declare(strict_types=1);

namespace App\Entity\ValueObject;

use Doctrine\ORM\Mapping as ORM;
use InvalidArgumentException;

/**
 * @ORM\Embeddable
 */
final class CategoryTitle
{
    /**
     * @ORM\Column(type="string", length=255)
     */
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