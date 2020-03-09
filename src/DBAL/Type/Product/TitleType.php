<?php

declare(strict_types=1);

namespace App\DBAL\Type\Product;

use App\Entity\ValueObject\Product\Title;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;
use InvalidArgumentException;

class TitleType extends StringType
{
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (! $value instanceof Title) {
            throw new InvalidArgumentException('Value must be instance of ' . Title::class);
        }
        $value = (string) $value;
        return parent::convertToDatabaseValue($value, $platform);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        $value = parent::convertToPHPValue($value, $platform);
        return new Title($value);
    }
}