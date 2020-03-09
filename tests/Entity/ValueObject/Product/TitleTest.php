<?php

namespace App\Tests\Entity\ValueObject\Product;

use App\Entity\ValueObject\Product\Title;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class TitleTest extends TestCase
{
    public function testCastToString()
    {
        $expectedValue = 'title';

        $title = new Title($expectedValue);
        $actualValue = (string) $title;

        $this->assertSame($expectedValue, $actualValue);
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
            [str_repeat('a', Title::MIN_LENGTH - 1)],
            [str_repeat('b', Title::MAX_LENGTH + 1)],
        ];
    }
}