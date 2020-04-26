<?php

declare(strict_types=1);

namespace App\Entity\ValueObject;

use Doctrine\ORM\Mapping as ORM;
use InvalidArgumentException;

/** @ORM\Embeddable */
final class ProductSlug
{
    /**
     * @ORM\Column(type="string", length=64, unique=true)
     * @var string
     */
    private $value;

    /**
     * ProductSlug constructor.
     * @param string $value
     */
    public function __construct(string $value)
    {
        if (preg_match('/^(?!-)([a-z0-9]|(?<!-)-)+(?<!-)$/', $value) !== 1) {
            throw new InvalidArgumentException('Slug указан неверно');
        }
        $this->value = $value;
    }

    public function __toString()
    {
        return $this->value;
    }
}