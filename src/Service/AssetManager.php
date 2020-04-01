<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\ValueObject\Asset;
use InvalidArgumentException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;

final class AssetManager
{
    /**
     * @var string
     */
    private $targetDirectory;
    /**
     * @var string|null
     */
    private $packageName;
    /**
     * @var \Symfony\Component\Filesystem\Filesystem
     */
    private $filesystem;

    public function __construct(string $targetDirectory, string $packageName = null)
    {
        $this->targetDirectory = $targetDirectory;
        $this->packageName = $packageName;
        $this->filesystem = new Filesystem();
    }

    /**
     * @param \Symfony\Component\HttpFoundation\File\File $file
     * @return \App\Entity\ValueObject\Asset
     */
    public function upload(File $file): Asset
    {
        do {
            $path = uniqid('', true) . '.' . $file->guessExtension();
            $targetFile = $this->targetDirectory . '/' . $path;
        } while ($this->filesystem->exists($targetFile));

        $this->filesystem->copy($file->getRealPath(), $targetFile);
        return new Asset($path, $this->packageName);
    }

    /**
     * @param \App\Entity\ValueObject\Asset $asset
     * @throws \Symfony\Component\Filesystem\Exception\IOException
     * @throws \InvalidArgumentException
     */
    public function delete(Asset $asset): void
    {
        if ($asset->getPackageName() !== $this->packageName) {
            throw new InvalidArgumentException(sprintf(
                'Значение packageName у $asset должно быть %s',
                $this->packageName ?? 'null'
            ));
        }
        $this->filesystem->remove($this->targetDirectory . '/' . $asset->getPath());
    }
}
