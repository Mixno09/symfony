<?php

declare(strict_types=1);

namespace App\Entity\ValueObject\Product;

class Description
{
    public const MIN_LENGTH = 5;
    /**
     * @var string
     */
    private $value;

    /**
     * Description constructor.
     * @param string $value
     */
    private function __construct(string $value)
    {
        $length = mb_strlen($value);

        if ($length < self::MIN_LENGTH) {
            throw new \InvalidArgumentException(sprintf(
                'Длина строки должна быть более чем %d символов',
                self::MIN_LENGTH));
        }
        $this->value = $value;
    }

    /**
     * @param string $description
     * @return static
     */
    public static function fromString(string $description): self
    {
        return new self($description);
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return $this->value;
    }
}