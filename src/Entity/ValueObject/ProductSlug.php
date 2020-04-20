<?php

declare(strict_types=1);

namespace App\Entity\ValueObject;

use Doctrine\ORM\Mapping as ORM;

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
        $this->value = $value;
    }

    public function __toString()
    {
        return $this->value;
    }
}