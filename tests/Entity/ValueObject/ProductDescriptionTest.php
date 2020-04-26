<?php

declare(strict_types=1);

namespace App\Tests\Entity\ValueObject;

use App\Entity\ValueObject\ProductDescription;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class ProductDescriptionTest extends TestCase
{
    public function testCreateDescription()
    {
        $value = 'Description';

        $description = new ProductDescription($value);

        $this->assertSame($value, (string) $description);
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