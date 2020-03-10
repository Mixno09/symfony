<?php

declare(strict_types=1);

namespace App\Entity\ValueObject\Product;

use Doctrine\ORM\Mapping as ORM;
use InvalidArgumentException;

/** @ORM\Embeddable */
class Description
{
    public const MIN_LENGTH = 5;
    /**
     * @ORM\Column(type="text")
     * @var string
     */
    private $value;

    /**
     * Description constructor.
     * @param string $value
     */
    public function __construct(string $value)
    {
        $length = mb_strlen($value);

        if ($length < self::MIN_LENGTH) {
            throw new InvalidArgumentException(sprintf(
                'Длина строки должна быть более чем %d символов',
                self::MIN_LENGTH));
        }
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->value;
    }
}