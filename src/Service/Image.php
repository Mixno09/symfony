<?php

declare(strict_types=1);

namespace App\Service;

final class Image implements ImageInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * Image constructor.
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}