<?php

declare(strict_types=1);

namespace App\Entity\ValueObject;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\JsonType;
use InvalidArgumentException;

class AssetType extends JsonType // todo возможно надо переопределить метод getName()
{
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (! $value instanceof Asset) {
            throw new InvalidArgumentException('Value must be instance of ' . Asset::class);
        }
        $value = [
            'path' => $value->getPath(),
            'packageName' => $value->getPackageName(),
        ];
        return parent::convertToDatabaseValue($value, $platform);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        $value = parent::convertToPHPValue($value, $platform);
        return new Asset($value['path'], $value['packageName']);
    }
}