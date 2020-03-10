<?php

declare(strict_types=1);

namespace App\UseCase\Product\DeleteProduct;

use App\Entity\Product;
use App\Service\AssetManager;
use Doctrine\ORM\EntityManagerInterface;
use RuntimeException;

final class Handler
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
     * @param \App\UseCase\Product\DeleteProduct\Command $command
     * @throws \Throwable
     */
    public function execute(Command $command): void
    {
        $product = $this->entityManager->find(Product::class, $command->id);
        if (! $product instanceof Product) {
            throw new RuntimeException("Продукт с ID={$command->id} не существует");
        }
        $this->entityManager->remove($product);
        $this->entityManager->flush();

        $image = $product->getImage();
        $this->assetManager->delete($image);
    }
}