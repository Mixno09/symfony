<?php

declare(strict_types=1);

namespace App\Messenger\Command;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\ValueObject\ProductDescription;
use App\Entity\ValueObject\Slug;
use App\Entity\ValueObject\Title;
use App\Repository\CategoryRepository;
use App\Service\AssetManager;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Throwable;

final class CreateProductHandler implements MessageHandlerInterface
{
    private EntityManagerInterface $entityManager;
    private AssetManager $assetManager;
    private CategoryRepository $categoryRepository;

    /**
     * Handler constructor.
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager
     * @param \App\Service\AssetManager $assetManager
     * @param \App\Repository\CategoryRepository $categoryRepository
     */
    public function __construct(EntityManagerInterface $entityManager, AssetManager $assetManager, \App\Repository\CategoryRepository $categoryRepository)
    {
        $this->entityManager = $entityManager;
        $this->assetManager = $assetManager;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @param \App\Messenger\Command\CreateProductCommand $command
     * @return void
     * @throws \Throwable
     */
    public function __invoke(CreateProductCommand $command): void
    {
        $image = $this->assetManager->upload($command->image);
        /** @var Category[] $categories */
        $categories = $this->categoryRepository->findAll();

        try {
            $product = new Product(
                Uuid::fromString($command->id),
                new Title($command->title),
                new Slug($command->slug),
                new ProductDescription($command->description),
                $image,
                $categories,
        );
            $this->entityManager->persist($product);
            $this->entityManager->flush();
        } catch (Throwable $exception) {
            $this->assetManager->delete($image);
            throw $exception;
        }
    }
}