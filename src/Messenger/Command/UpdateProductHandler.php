<?php

declare(strict_types=1);

namespace App\Messenger\Command;

use App\Entity\Product;
use App\Entity\ValueObject\ProductDescription;
use App\Entity\ValueObject\ProductImage;
use App\Entity\ValueObject\Title;
use App\Repository\CategoryRepository;
use App\Service\FileManager;
use Doctrine\ORM\EntityManagerInterface;
use LogicException;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Throwable;

final class UpdateProductHandler implements MessageHandlerInterface
{
    private EntityManagerInterface $entityManager;
    private FileManager $fileManager;
    private CategoryRepository $categoryRepository;

    /**
     * Handler constructor.
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager
     * @param \App\Service\FileManager $fileManager
     * @param \App\Repository\CategoryRepository $categoryRepository
     */
    public function __construct(EntityManagerInterface $entityManager, FileManager $fileManager, CategoryRepository $categoryRepository)
    {
        $this->entityManager = $entityManager;
        $this->fileManager = $fileManager;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @param \App\Messenger\Command\UpdateProductCommand $command
     * @throws \Throwable
     */
    public function __invoke(UpdateProductCommand $command): void
    {
        $id = Uuid::fromString($command->id);
        $product = $this->entityManager->find(Product::class, $id);
        if (! $product instanceof Product) {
            throw new LogicException("Продукта с ID={$id} не существует");
        }

        $categoriesId = array_map(
            fn(string $id): string => Uuid::fromString($id)->getBytes(),
            $command->categories
        );
        $categories = $this->categoryRepository->findBy(['id' => $categoriesId]);

        $image = null;
        $oldImage = null;
        if ($command->image instanceof File) {
            $image = ProductImage::create($command->image, $this->fileManager);
            $oldImage = $product->getImage();
        }

        try {
            $product->update(
                new Title($command->title),
                $categories,
                new ProductDescription($command->description),
                $image
            );
            $this->entityManager->flush();
        } catch (Throwable $exception) {
            if ($image instanceof ProductImage) {
                $image->delete($this->fileManager);
            }
            throw $exception;
        }

        if ($oldImage instanceof ProductImage) {
            $oldImage->delete($this->fileManager);
        }
    }
}