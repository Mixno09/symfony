<?php

declare(strict_types=1);

namespace App\Entity\ValueObject;

use Doctrine\ORM\Mapping as ORM;
use InvalidArgumentException;

/**
 * @ORM\Embeddable
 */
final class ProductDescription
{
    private const MIN_LENGTH = 5;
    /**
     * @ORM\Column(type="text")
     */
    private string $value;

    /**
     * Description constructor.
     * @param string $value
     */
    public function __construct(string $value)
    {
        if (! self::test($value)) {
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

    /**
     * @param string $value
     * @return bool
     */
    public static function test(string $value): bool
    {
        $length = mb_strlen($value);
        return ($length >= self::MIN_LENGTH);
    }
}