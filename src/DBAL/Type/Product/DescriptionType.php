<?php

declare(strict_types=1);

namespace App\DBAL\Type\Product;

use App\Entity\ValueObject\Product\Description;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\TextType;
use InvalidArgumentException;

class DescriptionType extends TextType
{
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (! $value instanceof Description) {
            throw new InvalidArgumentException('Value must be instance of ' . Description::class);
        }
        $value = (string) $value;
        return parent::convertToDatabaseValue($value, $platform);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        $value = parent::convertToPHPValue($value, $platform);
        return new Description($value);
    }
}