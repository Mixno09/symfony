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
    /**
     * @var \Knp\Component\Pager\Paginator
     */
    private $paginator;

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
        $query = $this->createQueryBuilder('p');
        return $this->paginator->paginate($query, $page, $limit);
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