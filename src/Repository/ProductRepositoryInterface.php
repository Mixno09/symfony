<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Product;

interface ProductRepositoryInterface
{
    public function save(Product $product): void;

    public function find(int $id): ?Product;
}