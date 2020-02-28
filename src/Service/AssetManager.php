<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\ValueObject\Asset;
use InvalidArgumentException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use function transliterator_transliterate;

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

    public function __construct(string $targetDirectory, string $packageName = null)
    {
        $this->targetDirectory = $targetDirectory;
        $this->packageName = $packageName;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $uploadedFile
     * @return \App\Entity\ValueObject\Asset
     * @throws \Symfony\Component\HttpFoundation\File\Exception\FileException
     */
    public function upload(UploadedFile $uploadedFile): Asset
    {
        $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
        $filename = $safeFilename . '-' . uniqid() . '.' . $uploadedFile->guessExtension();
        $uploadedFile->move($this->targetDirectory, $filename);
        return new Asset($filename, $this->packageName);
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
        (new Filesystem())->remove($this->targetDirectory . '/' . $asset->getPath());
    }
}
