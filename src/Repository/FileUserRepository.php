<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;
use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\Paginator;

class FileUserRepository implements UserRepositoryInterface, PaginatorAwareInterface
{
    /**
     * @var string
     */
    private $file;

    /**
     * @var \Knp\Component\Pager\Paginator
     */
    private $paginator;

    public function __construct(string $file)
    {
        $this->file = $file;
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
            if (strcasecmp($user->email, $email) === 0) {
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
        if (! is_file($this->file)) {
            return [];
        }
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

    public function paginate(int $page = 1, int $limit = 10, array $options = []): PaginationInterface
    {
        $target = $this->all();
        $pagination = $this->paginator->paginate($target, $page, $limit, $options);
        return $pagination;
    }

    /**
     * Sets the KnpPaginator instance.
     *
     * @param Paginator $paginator
     *
     * @return mixed
     */
    public function setPaginator(Paginator $paginator)
    {
        $this->paginator = $paginator;
    }

    public function find(int $id): ?User
    {
        $users = $this->all();
        foreach ($users as $user) {
            if ($user->id === $id) {
                return $user;
            }
        }
        return null;
    }
}