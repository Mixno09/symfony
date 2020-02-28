<?php

declare(strict_types=1);

namespace App\Entity\ValueObject\Product;

use InvalidArgumentException;

class Title
{
    public const MIN_LENGTH = 5;
    public const MAX_LENGTH = 255;

    /**
     * @var string
     */
    private $value;

    /**
     * Title constructor.
     * @param string $value
     * @throws \InvalidArgumentException
     */
    private function __construct(string $value)
    {
        $length = mb_strlen($value);
        if ($length < self::MIN_LENGTH || $length > self::MAX_LENGTH) {
            throw new InvalidArgumentException(sprintf(
                'Длина строки должна быть от %d до %d символов',
                self::MIN_LENGTH,
                self::MAX_LENGTH
            ));
        }
        $this->value = $value;
    }

    /**
     * @param string $title
     * @return static
     * @throws \InvalidArgumentException
     */
    public static function fromString(string $title): self
    {
        return new self($title);
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return $this->value;
    }
}