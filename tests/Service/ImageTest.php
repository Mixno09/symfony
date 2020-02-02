<?php

namespace App\Tests\Service;

use App\Service\Image;
use PHPUnit\Framework\TestCase;

class ImageTest extends TestCase
{
    public function testAccessors()
    {
        $nameExpected = 'test.jpg';

        $image = new Image($nameExpected);
        $nameActual = $image->getName();

        $this->assertEquals($nameExpected, $nameActual);
    }
}