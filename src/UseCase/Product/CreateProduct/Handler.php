<?php

declare(strict_types=1);

namespace App\UseCase\Product\CreateProduct;

use App\Entity\Product;
use App\Entity\ValueObject\ProductDescription;
use App\Entity\ValueObject\ProductTitle;
use App\Service\AssetManager;
use Doctrine\ORM\EntityManagerInterface;
use Throwable;

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
     * @param \App\UseCase\Product\CreateProduct\Command $command
     * @return int
     * @throws \Throwable
     */
    public function execute(Command $command): int
    {
        $image = $this->assetManager->upload($command->image);

        try {
            $product = new Product(
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
        return $product->getId();
    }
}