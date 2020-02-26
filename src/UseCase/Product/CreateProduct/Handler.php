<?php

declare(strict_types=1);

namespace App\UseCase\Product\CreateProduct;

use App\Entity\Product;
use App\Entity\ValueObject\Product\Title;
use App\Service\AssetManager;
use Doctrine\ORM\EntityManagerInterface;

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
     */
    public function execute(Command $command): int
    {
        $product = new Product(
            Title::fromString($command->title),
            $command->description,
            $this->assetManager->upload($command->image)
        );
        $this->entityManager->persist($product);
        $this->entityManager->flush();
        return $product->getId();
    }
}