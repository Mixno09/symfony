<?php

declare(strict_types=1);

namespace App\Messenger\Command;

use App\Entity\Product;
use App\Entity\ValueObject\ProductDescription;
use App\Entity\ValueObject\ProductImage;
use App\Entity\ValueObject\Slug;
use App\Entity\ValueObject\Title;
use App\Service\FileManager;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Throwable;

final class CreateProductHandler implements MessageHandlerInterface
{
    private EntityManagerInterface $entityManager;
    private FileManager $fileManager;

    /**
     * Handler constructor.
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager
     * @param \App\Service\FileManager $fileManager
     */
    public function __construct(EntityManagerInterface $entityManager, FileManager $fileManager)
    {
        $this->entityManager = $entityManager;
        $this->fileManager = $fileManager;
    }

    /**
     * @param \App\Messenger\Command\CreateProductCommand $command
     * @return void
     * @throws \Throwable
     */
    public function __invoke(CreateProductCommand $command): void
    {
        $image = ProductImage::create($command->image, $this->fileManager);

        try {
            $product = new Product(
                Uuid::fromString($command->id),
                new Title($command->title),
                new Slug($command->slug),
                new ProductDescription($command->description),
                $image,
            );
            $this->entityManager->persist($product);
            $this->entityManager->flush();
        } catch (Throwable $exception) {
            $image->delete($this->fileManager);
            throw $exception;
        }
    }
}