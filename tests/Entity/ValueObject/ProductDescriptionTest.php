<?php

namespace App\Tests\Entity\ValueObject;

use App\Entity\ValueObject\ProductDescription;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class ProductDescriptionTest extends TestCase
{
    public function testCastToString()
    {
        $expectedValue = 'Description';
        $description = new ProductDescription($expectedValue);

        $actualValue = (string) $description;

        $this->assertSame($expectedValue, $actualValue);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidValue()
    {
        $value = str_repeat('a', ProductDescription::MIN_LENGTH - 1);
        $this->expectException(InvalidArgumentException::class);
        new ProductDescription($value);
    }
}