<?php

declare(strict_types=1);

namespace App\Twig;

use App\Entity\ValueObject\Asset;
use Symfony\Component\Asset\Packages;
use Twig\Extension\RuntimeExtensionInterface;

final class FileRuntime implements RuntimeExtensionInterface
{
    private Packages $packages;

    public function __construct(Packages $packages)
    {
        $this->packages = $packages;
    }

    public function getUrl(Asset $asset): string
    {
        return $this->packages->getUrl(
            $asset->getPath(),
            $asset->getPackageName()
        );
    }
}