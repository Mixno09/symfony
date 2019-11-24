<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface UserRepositoryInterface
{
    public function save(User $user): void;

    public function getByEmail(string $email): ?User;

    public function paginate(int $page = 1, int $limit = 10, array $options = []): PaginationInterface;

    public function find(int $id): ?User;

    public function delete(int $id): void;
}