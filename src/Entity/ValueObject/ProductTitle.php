<?php

declare(strict_types=1);

namespace App\Entity\ValueObject;

use Doctrine\ORM\Mapping as ORM;
use InvalidArgumentException;

/** @ORM\Embeddable */
class ProductTitle
{
    public const MIN_LENGTH = 5;
    public const MAX_LENGTH = 255;

    /**
     * @ORM\Column(type="string", length=255)
     * @var string
     */
    private $value;

    /**
     * Title constructor.
     * @param string $value
     * @throws \InvalidArgumentException
     */
    public function __construct(string $value)
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
     * @return string
     */
    public function __toString()
    {
        return $this->value;
    }
}