<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;

interface UserRepositoryInterface
{
    public function save(User $user);

    public function getByEmail(string $email): ?User;
}