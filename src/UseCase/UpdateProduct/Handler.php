<?php

declare(strict_types=1);

namespace App\UseCase\UpdateProduct;

use App\Entity\Product;
use App\Entity\ValueObject\Asset;
use App\Entity\ValueObject\ProductDescription;
use App\Entity\ValueObject\ProductTitle;
use App\Service\AssetManager;
use Doctrine\ORM\EntityManagerInterface;
use LogicException;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Throwable;

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
     * @param \App\UseCase\UpdateProduct\Command $command
     * @throws \Throwable
     */
    public function __invoke(Command $command): void
    {
        $id = Uuid::fromString($command->id);
        $product = $this->entityManager->find(Product::class, $id);
        if (! $product instanceof Product) {
            throw new LogicException("Продукта с ID={$id} не существует");
        }

        $image = null;
        $oldImage = null;
        if ($command->image instanceof File) {
            $image = $this->assetManager->upload($command->image);
            $oldImage = $product->getImage();
        }

        try {
            $product->update(
                new ProductTitle($command->title),
                new ProductDescription($command->description),
                $image
            );
            $this->entityManager->flush();
        } catch (Throwable $exception) {
            if ($image instanceof Asset) {
                $this->assetManager->delete($image);
            }
            throw $exception;
        }

        if ($oldImage instanceof Asset) {
            $this->assetManager->delete($oldImage);
        }
    }
}