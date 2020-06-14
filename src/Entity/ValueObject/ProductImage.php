<?php

declare(strict_types=1);

namespace App\Entity\ValueObject;

use App\Service\FileManager;
use Doctrine\ORM\Mapping as ORM;
use LogicException;
use Symfony\Component\HttpFoundation\File\File;

/** @ORM\Embeddable */
final class ProductImage implements Asset
{
    private const PACKAGE_NAME = 'product';
    private const DIR = 'product';

    /**
     * @ORM\Column(type="string")
     */
    private ?string $path;

    public static function create(File $file, FileManager $fileManager): self
    {
        $fileName = $fileManager->upload($file, self::DIR);

        return new self($fileName);
    }

    private function __construct(string $path)
    {
        $this->path = $path;
    }

    public function delete(FileManager $fileManager): void
    {
        $this->checkDelete();

        $fileManager->delete(self::DIR, $this->path);
        $this->path = null;
    }

    public function getPath(): string
    {
        $this->checkDelete();

        return $this->path;
    }

    public function getPackageName(): string
    {
        $this->checkDelete();

        return self::PACKAGE_NAME;
    }

    private function checkDelete(): void
    {
        if ($this->path === null) {
            throw new LogicException('Изображение было удалено');
        }
    }
}