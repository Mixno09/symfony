<?php

declare(strict_types=1);

namespace App\Tests\Entity\ValueObject;

use App\Entity\ValueObject\Title;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class TitleTest extends TestCase
{
    /**
     * @dataProvider validValueProvider
     * @param string $value
     */
    public function testCreateTitle(string $value)
    {
        $title = new Title($value);

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
        new Title($value);
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
        $test = Title::test($value);

        $this->assertTrue($test);
    }

    /**
     * @dataProvider invalidValueProvider
     * @param string $value
     */
    public function testTestReturnFalse(string $value)
    {
        $test = Title::test($value);

        $this->assertFalse($test);
    }
}