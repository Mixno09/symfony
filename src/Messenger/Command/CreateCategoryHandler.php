<?php

declare(strict_types=1);

namespace App\Messenger\Command;

use App\Entity\Category;
use App\Entity\ValueObject\Slug;
use App\Entity\ValueObject\Title;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class CreateCategoryHandler implements MessageHandlerInterface
{
    private EntityManagerInterface $entityManager;

    /**
     * Handler constructor.
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param \App\Messenger\Command\CreateCategoryCommand $command
     * @return void
     */
    public function __invoke(CreateCategoryCommand $command): void
    {
        $category = new Category(
            Uuid::fromString($command->id),
            new Title($command->title),
            new Slug($command->slug)
        );
        $this->entityManager->persist($category);
        $this->entityManager->flush();
    }
}