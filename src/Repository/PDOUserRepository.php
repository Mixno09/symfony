<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;
use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use Knp\Component\Pager\Event\Subscriber\Paginate\Callback\CallbackPagination;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\Paginator;
use PDO;
use ReflectionClass;

class PDOUserRepository implements UserRepositoryInterface, PaginatorAwareInterface
{
    public const TABLE = 'users';

    /**
     * @var \Knp\Component\Pager\Paginator
     */
    private $paginator;
    /**
     * @var \PDO
     */
    private $pdo;

    /**
     * PDOUserRepository constructor.
     * @param \PDO $pdo
     */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function find(int $id): ?User
    {
        $sql = 'SELECT * FROM ' . self::TABLE . ' WHERE id = :id';
        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(':id', $id, PDO::PARAM_INT);
        $statement->execute();
        $data = $statement->fetch(PDO::FETCH_ASSOC);
        if (! is_array($data)) {
            return null;
        }
        $user = $this->hydrate($data);
        return $user;
    }

    public function getByEmail(string $email): ?User
    {
        $sql = 'SELECT * FROM ' . self::TABLE . ' WHERE email = :email';
        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(':email', $email, PDO::PARAM_STR);
        $statement->execute();
        $data = $statement->fetch(PDO::FETCH_ASSOC);
        if (! is_array($data)) {
            return null;
        }
        $user = $this->hydrate($data);
        return $user;
    }

    public function paginate(int $page = 1, int $limit = 10, array $options = []): PaginationInterface
    {
        $count = function () {
            $sql = 'SELECT count(*) FROM ' . self::TABLE;
            $statement = $this->pdo->query($sql);
            $count = $statement->fetch(PDO::FETCH_COLUMN);
            return $count;
        };
        $items = function ($offset, $limit) {
            $sql = 'SELECT * FROM ' . self::TABLE . ' ORDER BY id ASC LIMIT ' . $limit . ' OFFSET ' . $offset;
            $statement = $this->pdo->query($sql);
            $items = [];
            foreach ($statement->fetchAll(PDO::FETCH_ASSOC) as $data) {
                $items[] = $this->hydrate($data);
            }

            return $items;
        };
        $target = new CallbackPagination($count, $items);
        $pagination = $this->paginator->paginate($target, $page, $limit);
        return $pagination;
    }

    public function save(User $user): void
    {
        if ($user->id === 0) {
            $this->insert($user);
        } else {
            $this->update($user);
        }
    }

    private function insert(User $user): void
    {
        $sql = 'INSERT INTO ' . self::TABLE . ' (email, admin, roles, password) VALUES (:email, :admin, :roles, :password)';
        $statement = $this->pdo->prepare($sql);
        $data = $this->extract($user);
        $statement->bindValue(':email', $data['email'], PDO::PARAM_STR);
        $statement->bindValue(':admin', $data['admin'], PDO::PARAM_INT);
        $statement->bindValue(':roles', $data['roles'], PDO::PARAM_STR);
        $statement->bindValue(':password', $data['password'], PDO::PARAM_STR);
        $statement->execute();

        $id = (int) $this->pdo->lastInsertId();
        $user->id = $id;
    }

    private function update(User $user): void
    {
        $sql = 'UPDATE ' . self::TABLE . ' SET email = :email, admin = :admin, roles = :roles, password = :password WHERE id = :id';
        $statement = $this->pdo->prepare($sql);
        $data = $this->extract($user);
        $statement->bindValue(':id', $data['id'], PDO::PARAM_INT);
        $statement->bindValue(':email', $data['email'], PDO::PARAM_STR);
        $statement->bindValue(':admin', $data['admin'], PDO::PARAM_INT);
        $statement->bindValue(':roles', $data['roles'], PDO::PARAM_STR);
        $statement->bindValue(':password', $data['password'], PDO::PARAM_STR);
        $statement->execute();
    }

    public function delete(int $id): void
    {
        $sql = 'DELETE FROM ' . self::TABLE . ' WHERE id = :id';
        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(':id', $id, PDO::PARAM_INT);
        $statement->execute();
    }

    private function hydrate(array $data): User
    {
        $user = new User();
        $user->id = $data['id'];
        $user->email = $data['email'];
        $user->admin = ($data['admin'] === 1);

        $reflectionClass = new ReflectionClass(User::class);
        $reflectionProperty = $reflectionClass->getProperty('password');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($user, $data['password']);

        $roles = json_decode($data['roles']);
        $user->setRoles($roles);
        return $user;
    }

    private function extract(User $user): array
    {
        $data = [];
        $data['id'] = $user->id;
        $data['email'] = $user->email;
        $data['admin'] = ($user->admin ? 1 : 0);

        $reflectionClass = new ReflectionClass(User::class);
        $reflectionProperty = $reflectionClass->getProperty('password');
        $reflectionProperty->setAccessible(true);
        $password = $reflectionProperty->getValue($user);
        $data['password'] = $password;

        $reflectionProperty = $reflectionClass->getProperty('roles');
        $reflectionProperty->setAccessible(true);
        $roles = $reflectionProperty->getValue($user);
        $roles = json_encode($roles);
        $data['roles'] = $roles;

        return $data;
    }

    /**
     * @inheritDoc
     */
    public function setPaginator(Paginator $paginator)
    {
        $this->paginator = $paginator;
    }
}