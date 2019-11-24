<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Product;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface ProductRepositoryInterface
{
    public function save(Product $product): void;

    public function find(int $id): ?Product;

    public function paginate(int $page = 1, int $limit = 10, array $options = []): PaginationInterface;

    public function delete(int $id): void;

    /**
     * @return \App\Entity\Product[]
     */
    public function newest(int $count): array;
}