<?php

declare(strict_type=1);

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\Paginator;

final class ProductRepository extends ServiceEntityRepository implements PaginatorAwareInterface
{
    private Paginator $paginator;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * @param int $page
     * @param int $limit
     * @return \Knp\Component\Pager\Pagination\PaginationInterface
     */
    public function pagination(int $page, int $limit = 10): PaginationInterface
    {
        $query = $this->createQueryBuilder('p')->orderBy('p.title.value', 'ASC');
        return $this->paginator->paginate($query, $page, $limit);
    }

    /**
     * @param int $count
     * @return \App\Entity\Product[]
     */
    public function newest(int $count = 9): array
    {
        $query = $this
            ->createQueryBuilder('p')
            ->orderBy('p.id', 'DESC')
            ->setMaxResults($count)
            ->getQuery();
        return $query->getResult();
    }

    /**
     * @param string $slug
     * @return \App\Entity\Product|null
     */
    public function findBySlug(string $slug): ?Product
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