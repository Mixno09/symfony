<?php

declare(strict_types=1);

namespace App\UseCase\Product\CreateProduct;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;

final class Handler
{
    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    private $entityManager;

    /**
     * Handler constructor.
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param \App\UseCase\Product\CreateProduct\Command $command
     * @return int
     */
    public function execute(Command $command): int
    {
        $product = new Product(
            $command->getTitle(),
            $command->getDescription(),
            $command->getImage()
        );
        $this->entityManager->persist($product);
        $this->entityManager->flush();
        return $product->getId();
    }
}