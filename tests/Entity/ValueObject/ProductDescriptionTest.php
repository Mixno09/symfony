<?php

declare(strict_types=1);

namespace App\Tests\Entity\ValueObject;

use App\Entity\ValueObject\ProductDescription;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class ProductDescriptionTest extends TestCase
{
    /**
     * @dataProvider validValueProvider
     * @param string $value
     */
    public function testCreateDescription(string $value)
    {
        $description = new ProductDescription($value);

        $this->assertSame($value, (string) $description);
    }

    public function validValueProvider()
    {
        return [
            ['55555'],
            ['666666'],
            ['description'],
        ];
    }

    /**
     * @dataProvider invalidValueProvider
     * @param string $value
     */
    public function testInvalidValue(string $value)
    {
        $this->expectException(InvalidArgumentException::class);
        new ProductDescription($value);
    }

    public function invalidValueProvider()
    {
        return [
            [''],
            ['4444'],
            ['1'],
        ];
    }
    /**
     * @dataProvider validValueProvider
     * @param string $value
     */
    public function testTestReturnTrue(string $value)
    {
        $test = ProductDescription::test($value);

        $this->assertTrue($test);
    }

    /**
     * @dataProvider invalidValueProvider
     * @param string $value
     */
    public function testTestReturnFalse(string $value)
    {
        $test = ProductDescription::test($value);

        $this->assertFalse($test);
    }
}