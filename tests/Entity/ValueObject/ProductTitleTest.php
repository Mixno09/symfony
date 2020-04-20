<?php

namespace App\Tests\Entity\ValueObject;

use App\Entity\ValueObject\ProductTitle;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class ProductTitleTest extends TestCase
{
    public function testCreateTitle()
    {
        $value = 'title';

        $title = new ProductTitle($value);

        $this->assertSame($value, (string) $title);
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