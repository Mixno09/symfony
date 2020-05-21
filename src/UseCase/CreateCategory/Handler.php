<?php

declare(strict_types=1);

namespace App\UseCase\CreateCategory;

use App\Entity\Category;
use App\Entity\ValueObject\Slug;
use App\Entity\ValueObject\Title;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class Handler implements MessageHandlerInterface
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
     * @param \App\UseCase\CreateCategory\Command $command
     * @return void
     */
    public function __invoke(Command $command): void
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