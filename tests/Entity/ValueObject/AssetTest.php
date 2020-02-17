<?php

namespace App\Tests\Entity\ValueObject;

use App\Entity\ValueObject\Asset;
use PHPUnit\Framework\TestCase;

class AssetTest extends TestCase
{
    /**
     * @dataProvider accessorsProvider
     * @param $pathExpected
     * @param $packageNameExpected
     */
    public function testAccessors($pathExpected, $packageNameExpected)
    {
        $image = new Asset($pathExpected, $packageNameExpected);
        $pathActual = $image->getPath();
        $packageNameActual = $image->getPackageName();

        $this->assertEquals($pathExpected, $pathActual);
        $this->assertEquals($packageNameExpected, $packageNameActual);
    }

    public function accessorsProvider()
    {
        return [
            ['image.jpg', 'product'],
            ['image.jpg', null],
        ];
    }
}