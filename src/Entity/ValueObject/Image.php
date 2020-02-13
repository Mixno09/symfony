<?php

declare(strict_types=1);

namespace App\Entity\ValueObject;

final class Image implements Asset
{
    /**
     * @var string
     */
    private $path;
    /**
     * @var string|null
     */
    private $packageName;

    /**
     * Image constructor.
     * @param string $path
     * @param string|null $packageName
     */
    public function __construct(string $path, string $packageName = null)
    {
        $this->path = $path;
        $this->packageName = $packageName;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getPackageName(): ?string
    {
        return $this->packageName;
    }
}