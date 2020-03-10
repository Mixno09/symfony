<?php

declare(strict_types=1);

namespace App\Entity\ValueObject;

use Doctrine\ORM\Mapping as ORM;

/** @ORM\Embeddable */
final class Asset
{
    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $path;
    /**
     * @ORM\Column(type="string", nullable=true)
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

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return string|null
     */
    public function getPackageName(): ?string
    {
        return $this->packageName;
    }
}