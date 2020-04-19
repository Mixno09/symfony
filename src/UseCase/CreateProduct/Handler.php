<?php

declare(strict_types=1);

namespace App\UseCase\CreateProduct;

use App\Entity\Product;
use App\Entity\ValueObject\ProductDescription;
use App\Entity\ValueObject\ProductTitle;
use App\Service\AssetManager;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;
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
     * @param \App\UseCase\CreateProduct\Command $command
     * @return void
     * @throws \Throwable
     */
    public function __invoke(Command $command): void
    {
        $image = $this->assetManager->upload($command->image);

        try {
            $product = new Product(
                Uuid::fromString($command->id),
                new ProductTitle($command->title),
                new ProductDescription($command->description),
                $image
            );
            $this->entityManager->persist($product);
            $this->entityManager->flush();
        } catch (Throwable $exception) {
            $this->assetManager->delete($image);
            throw $exception;
        }
    }
}