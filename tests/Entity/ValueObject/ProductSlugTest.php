<?php

namespace App\Tests\Entity\ValueObject;

use App\Entity\ValueObject\ProductSlug;
use PHPUnit\Framework\TestCase;

class ProductSlugTest extends TestCase
{
    public function testCreateSlug()
    {
        $value = 'test';

        $slug = new ProductSlug($value);

        $this->assertSame($value, (string) $slug);
    }
}
