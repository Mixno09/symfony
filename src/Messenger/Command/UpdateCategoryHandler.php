<?php

declare(strict_types=1);

namespace App\Messenger\Command;

use App\Entity\Category;
use Doctrine\ORM\EntityManager;
use LogicException;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class UpdateCategoryHandler implements MessageHandlerInterface
{
    private EntityManager $entityManager;

    public function __invoke(UpdateCategoryCommand $command): void
    {
        $id = Uuid::fromString($command->id);
        $category = $this->entityManager->find(Category::class, $id);
        if (! $category instanceof Category) {
            throw new LogicException("Категории с ID={$id} не существует");
        }

    }

}