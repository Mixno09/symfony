<?php

declare(strict_types=1);

namespace App\Messenger\Command;

use App\Entity\Category;
use App\Entity\ValueObject\Slug;
use App\Entity\ValueObject\Title;
use Doctrine\ORM\EntityManagerInterface;
use LogicException;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class UpdateCategoryHandler implements MessageHandlerInterface
{
    private EntityManagerInterface $entityManager;

    /**
     * UpdateCategoryHandler constructor.
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function __invoke(UpdateCategoryCommand $command): void
    {
        $id = Uuid::fromString($command->id);
        $category = $this->entityManager->find(Category::class, $id);
        if (! $category instanceof Category) {
            throw new LogicException("Категории с ID={$id} не существует");
        }

        $category->update(new Title($command->title), new Slug($command->slug));
        $this->entityManager->flush();
    }

}