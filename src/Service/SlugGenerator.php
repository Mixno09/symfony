<?php

declare(strict_types=1);

namespace App\Service;

final class SlugGenerator
{
    const PATTERN = [
        'а' => 'a',
        'б' => 'b',
        'в' => 'v',
        'г' => 'g',
        'д' => 'd',
        'е' => 'e',
        'ё' => 'e',
        'ж' => 'j',
        'з' => 'z',
        'и' => 'i',
        'й' => 'i',
        'к' => 'k',
        'л' => 'l',
        'м' => 'm',
        'н' => 'n',
        'о' => 'o',
        'п' => 'p',
        'р' => 'r',
        'с' => 's',
        'т' => 't',
        'у' => 'u',
        'ф' => 'f',
        'х' => 'ch',
        'ц' => 'c',
        'ч' => 'ch',
        'ш' => 'sh',
        'щ' => 'sc',
        'ы' => 'y',
        'э' => 'e',
        'ю' => 'iu',
        'я' => 'ia',
    ];

    public function generate(string $text): string
    {
        $text = mb_strtolower($text);
        $text = str_replace(
            array_keys(self::PATTERN),
            array_values(self::PATTERN),
            $text
        );
        $text = preg_replace('/[^a-z0-9\-]/', '-', $text);
        $text = trim($text, '-');
        $text = preg_replace('/--+/', '-', $text);
        return $text;
    }
}