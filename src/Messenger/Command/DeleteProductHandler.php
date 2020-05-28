<?php

declare(strict_types=1);

namespace App\Messenger\Command;

use App\Entity\Product;
use App\Service\AssetManager;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;
use RuntimeException;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class DeleteProductHandler implements MessageHandlerInterface
{
    private EntityManagerInterface $entityManager;
    private AssetManager $assetManager;

    /**
     * Handler constructor.
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager
     * @param \App\Service\AssetManager $assetManager
     */
    public function __construct(EntityManagerInterface $entityManager, AssetManager $assetManager)
    {
        $this->entityManager = $entityManager;
        $this->assetManager = $assetManager;
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

        $image = $product->getImage();
        $this->assetManager->delete($image);
    }
}