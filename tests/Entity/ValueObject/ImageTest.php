<?php

namespace App\Tests\Entity\ValueObject;

use App\Entity\ValueObject\Image;
use PHPUnit\Framework\TestCase;

class ImageTest extends TestCase
{
    /**
     * @dataProvider accessorsProvider
     */
    public function testAccessors($pathExpected, $packageNameExpected)
    {
        $image = new Image($pathExpected, $packageNameExpected);
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