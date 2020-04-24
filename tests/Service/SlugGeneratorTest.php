<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Service\SlugGenerator;
use PHPUnit\Framework\TestCase;

class SlugGeneratorTest extends TestCase
{
    /**
     * @dataProvider generateSlugProvider
     * @param string $text
     * @param string $standard
     */
    public function testGenerateSlug(string $text, string $standard)
    {
        $slugGenerator = new SlugGenerator();

        $slug = $slugGenerator->generate($text);

        $this->assertSame($standard, $slug);
    }

    public function generateSlugProvider()
    {
        return [
            ['', ''],
            ['abc', 'abc'],
            ['ABC', 'abc'],
            ['123', '123'],
            ['абв', 'abv'],
            ['АБВ', 'abv'],
            [' ', ''],
            ['a b', 'a-b'],
            ['  a b  ', 'a-b'],
            ['@#$', ''],
            ['a#b$c', 'a-b-c'],
            ['@@a#b$$', 'a-b'],
            ['a#####b', 'a-b'],
            ['-', ''],
            ['   Мама мыла раму. 5 раз   ', 'mama-myla-ramu-5-raz'],
            ['--N@ic@e %%%to me@#at yo$$u', 'n-ic-e-to-me-at-yo-u'],
        ];
    }
}