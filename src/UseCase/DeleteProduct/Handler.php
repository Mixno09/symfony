<?php

declare(strict_types=1);

namespace App\UseCase\DeleteProduct;

use App\Entity\Product;
use App\Service\AssetManager;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;
use RuntimeException;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class Handler implements MessageHandlerInterface
{
    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var \App\Service\AssetManager
     */
    private $assetManager;

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
     * @param \App\UseCase\DeleteProduct\Command $command
     * @throws \Throwable
     */
    public function __invoke(Command $command): void
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