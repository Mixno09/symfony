<?php

namespace App\Tests\Entity\ValueObject;

use App\Entity\ValueObject\ProductTitle;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class ProductTitleTest extends TestCase
{
    public function testCastToString()
    {
        $expectedValue = 'title';

        $title = new ProductTitle($expectedValue);
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
        new ProductTitle($value);
    }

    public function invalidValueProvider()
    {
        return [
            [str_repeat('a', ProductTitle::MIN_LENGTH - 1)],
            [str_repeat('b', ProductTitle::MAX_LENGTH + 1)],
        ];
    }
}