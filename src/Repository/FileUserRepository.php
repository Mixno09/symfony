<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;

class FileUserRepository implements UserRepositoryInterface
{
    /**
     * @var string
     */
    private $file;

    public function __construct(string $file)
    {
        $this->file = $file;

        if (! is_file($file)) {
            $this->persist([]);
        }
    }

    public function save(User $user)
    {
        $users = $this->all();
        if ($user->id === 0) {
            $id = 0;
            foreach ($users as $value) {
                if ($id < $value->id) {
                    $id = $value->id;
                }
            }
            $user->id = $id + 1;
            $users[] = $user;
        } else {
            foreach ($users as $key => $value) {
                if ($value->id === $user->id) {
                    $users[$key] = $user;
                    break;
                }
            }
        }
        $this->persist($users);
    }

    public function getByEmail(string $email): ?User
    {
        $users = $this->all();
        foreach ($users as $user) {
            if (strcasecmp($user->getEmail(), $email) === 0) {
                return $user;
            }
        }
        return null;
    }

    /**
     * @return \App\Entity\User[]
     */
    private function all(): array
    {
        $content = file_get_contents($this->file);
        $users = unserialize($content);
        return $users;
    }

    /**
     * @param \App\Entity\User[] $users
     */
    private function persist(array $users): void
    {
        $content = serialize($users);
        file_put_contents($this->file, $content);
    }
}