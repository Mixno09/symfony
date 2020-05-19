<?php

declare(strict_type=1);

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\Paginator;

final class CategoryRepository extends ServiceEntityRepository implements PaginatorAwareInterface
{
    private Paginator $paginator;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    /**
     * @param int $page
     * @param int $limit
     * @return \Knp\Component\Pager\Pagination\PaginationInterface
     */
    public function pagination(int $page, int $limit = 10): PaginationInterface
    {
        $query = $this->createQueryBuilder('c');
        return $this->paginator->paginate($query, $page, $limit);
    }

    /**
     * @param string $slug
     * @return \App\Entity\Category|null
     */
    public function findBySlug(string $slug): ?Category
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->findOneBy(['slug.value' => $slug]);
    }

    /**
     * @inheritDoc
     */
    public function setPaginator(Paginator $paginator): PaginatorAwareInterface
    {
        $this->paginator = $paginator;
        return $this;
    }
}