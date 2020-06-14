<?php

declare(strict_types=1);

namespace App\Messenger\Command;

use App\Entity\Product;
use App\Service\FileManager;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;
use RuntimeException;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class DeleteProductHandler implements MessageHandlerInterface
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
     * @param \App\Messenger\Command\DeleteProductCommand $command
     * @throws \Throwable
     */
    public function __invoke(DeleteProductCommand $command): void
    {
        $id = Uuid::fromString($command->id);
        $product = $this->entityManager->find(Product::class, $id);
        if (! $product instanceof Product) {
            throw new RuntimeException("Продукт с ID={$id} не существует");
        }
        $this->entityManager->remove($product);
        $this->entityManager->flush();

        $product->getImage()->delete($this->fileManager);
    }
}