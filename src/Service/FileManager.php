<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;

final class FileManager
{
    private string $baseDir;
    private Filesystem $filesystem;

    public function __construct(string $baseDir)
    {
        $this->baseDir = $baseDir;
        $this->filesystem = new Filesystem();
    }

    public function upload(File $file, string $dir): string
    {
        do {
            $fileName = uniqid('', true) . '.' . $file->guessExtension();
            $targetFile = $this->absolutePath($dir, $fileName);
        } while ($this->filesystem->exists($targetFile));

        $this->filesystem->copy(
            $file->getRealPath(),
            $targetFile
        );
        return $fileName;
    }

    public function delete(string $dir, string $fileName): void
    {
        $this->filesystem->remove(
            $this->absolutePath($dir, $fileName)
        );
    }

    private function absolutePath(string $dir, string $fileName): string
    {
        return $this->baseDir . '/' . $dir . '/' . $fileName;
    }
}