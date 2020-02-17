<?php

declare(strict_types=1);

namespace App\Tests\Entity\ValueObject;

use App\Entity\ValueObject\Asset;

final class AssetFactory
{
    /**
     * @return \App\Entity\ValueObject\Asset
     */
    public static function newAsset(): Asset
    {
        return new Asset('image.jpg');
    }
}