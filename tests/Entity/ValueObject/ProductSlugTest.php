<?php

declare(strict_types=1);

namespace App\Tests\Entity\ValueObject;

use App\Entity\ValueObject\ProductSlug;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class ProductSlugTest extends TestCase
{
    /**
     * @dataProvider createSlugProvider
     * @param string $value
     */
    public function testCreateSlug(string $value)
    {
        $slug = new ProductSlug($value);

        $this->assertSame($value, (string) $slug);
    }

    /**
     * @return array
     */
    public function createSlugProvider()
    {
        return [
            ['abc'],
            ['123'],
            ['a-b'],
            ['1-2'],
            ['a-1-b'],
        ];
    }

    /**
     * @dataProvider invalidSlugProvider
     * @param string $value
     */
    public function testInvalidSlug(string $value)
    {
        $this->expectException(InvalidArgumentException::class);
        new ProductSlug($value);
    }

    /**
     * @return array
     */
    public function invalidSlugProvider()
    {
        return [
            [''],
            ['ABC'],
            ['@#$'],
            ['абв'],
            ['-abc'],
            ['abc-'],
            ['a--b'],
        ];
    }
}
