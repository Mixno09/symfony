<?php

declare(strict_types=1);

namespace App\Entity\ValueObject;

use Doctrine\ORM\Mapping as ORM;
use InvalidArgumentException;

/**
 * @ORM\Embeddable
 */
final class Title
{
    private const MIN_LENGTH = 5;
    private const MAX_LENGTH = 255;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $value;

    /**
     * Title constructor.
     * @param string $value
     * @throws \InvalidArgumentException
     */
    public function __construct(string $value)
    {
        if (! self::test($value)) {
            throw new InvalidArgumentException(sprintf(
                'Длина строки должна быть от %d до %d символов',
                self::MIN_LENGTH,
                self::MAX_LENGTH
            ));
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
        return ($length >= self::MIN_LENGTH && $length <= self::MAX_LENGTH);
    }
}