<?php

declare(strict_types=1);

namespace App\Tests\Entity\ValueObject;

use App\Entity\ValueObject\ProductTitle;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class ProductTitleTest extends TestCase
{
    /**
     * @dataProvider validValueProvider
     * @param string $value
     */
    public function testCreateTitle(string $value)
    {
        $title = new ProductTitle($value);

        $this->assertSame($value, (string) $title);
    }

    public function validValueProvider()
    {
        return [
            [str_repeat('a', 5)],
            [str_repeat('a', 6)],
            [str_repeat('a', 254)],
            [str_repeat('a', 255)]
        ];
    }

    /**
     * @dataProvider invalidValueProvider
     * @param string $value
     */
    public function testInvalidValue(string $value)
    {
        $this->expectException(InvalidArgumentException::class);
        new ProductTitle($value);
    }

    public function invalidValueProvider()
    {
        return [
            [''],
            [str_repeat('a', 4)],
            [str_repeat('b', 256)],
        ];
    }

    /**
     * @dataProvider validValueProvider
     * @param string $value
     */
    public function testTestReturnTrue(string $value)
    {
        $test = ProductTitle::test($value);

        $this->assertTrue($test);
    }

    /**
     * @dataProvider invalidValueProvider
     * @param string $value
     */
    public function testTestReturnFalse(string $value)
    {
        $test = ProductTitle::test($value);

        $this->assertFalse($test);
    }
}