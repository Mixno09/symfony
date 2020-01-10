<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Types;
use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use Knp\Component\Pager\Event\Subscriber\Paginate\Callback\CallbackPagination;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\Paginator;
use ReflectionClass;

class DBALUserRepository implements UserRepositoryInterface, PaginatorAwareInterface
{
    public const TABLE = 'users';

    /**
     * @var \Knp\Component\Pager\Paginator
     */
    private $paginator;
    /**
     * @var \Doctrine\DBAL\Connection
     */
    private $connection;

    /**
     * DBALUserRepository constructor.
     * @param \Doctrine\DBAL\Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function find(int $id): ?User
    {
        $sql = 'SELECT * FROM ' . self::TABLE . ' WHERE id = :id';
        $data = $this->connection->fetchAssoc($sql, ['id' => $id], ['id' => Types::INTEGER]);
        if (! is_array($data)) {
            return null;
        }
        $data['admin'] = $this->connection->convertToPHPValue($data['admin'], Types::BOOLEAN);
        $data['roles'] = $this->connection->convertToPHPValue($data['roles'], Types::JSON);
        $user = $this->hydrate($data);
        return $user;
    }

    public function getByEmail(string $email): ?User
    {
        $sql = 'SELECT * FROM ' . self::TABLE . ' WHERE email = :email';
        $data = $this->connection->fetchAssoc($sql, ['email' => $email], ['email' => Types::STRING]);
        if (! is_array($data)) {
            return null;
        }

        $data['admin'] = $this->connection->convertToPHPValue($data['admin'], Types::BOOLEAN);
        $data['roles'] = $this->connection->convertToPHPValue($data['roles'], Types::JSON);

        $user = $this->hydrate($data);
        return $user;
    }

    public function paginate(int $page = 1, int $limit = 10, array $options = []): PaginationInterface
    {
        $count = function () {
            $sql = 'SELECT count(*) FROM ' . self::TABLE;
            $count = $this->connection->fetchColumn($sql);
            return $count;
        };
        $items = function ($offset, $limit) {
            $sql = 'SELECT * FROM ' . self::TABLE . ' ORDER BY id ASC LIMIT ' . $limit . ' OFFSET ' . $offset;
            $items = [];
            foreach ($this->connection->fetchAll($sql) as $data) {
                $data['admin'] = $this->connection->convertToPHPValue($data['admin'], Types::BOOLEAN);
                $data['roles'] = $this->connection->convertToPHPValue($data['roles'], Types::JSON);
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
        $data = $this->extract($user);

        $this->connection->insert(
            self::TABLE,
            [
                'email' => $data['email'],
                'admin' => $data['admin'],
                'roles' => $data['roles'],
                'password' => $data['password'],
            ],
            [
                'email' => Types::STRING,
                'admin' => Types::BOOLEAN,
                'roles' => Types::JSON,
                'password' => Types::STRING,
            ]
        );

        $id = (int) $this->connection->lastInsertId();
        $user->id = $id;
    }

    private function update(User $user): void
    {
        $data = $this->extract($user);
        $this->connection->update(
            self::TABLE,
            [
                'email' => $data['email'],
                'admin' => $data['admin'],
                'roles' => $data['roles'],
                'password' => $data['password'],
            ],
            [
                'id' => $data['id'],
            ],
            [
                'id' => Types::INTEGER,
                'email' => Types::STRING,
                'admin' => Types::BOOLEAN,
                'roles' => Types::JSON,
                'password' => Types::STRING,
            ]
        );
    }

    public function delete(int $id): void
    {
        $this->connection->delete(self::TABLE, ['id' => $id], ['id' => Types::INTEGER]);
    }

    private function hydrate(array $data): User
    {
        $user = new User();
        $user->id = $data['id'];
        $user->email = $data['email'];
        $user->admin = $data['admin'];

        $reflectionClass = new ReflectionClass(User::class);
        $reflectionProperty = $reflectionClass->getProperty('password');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($user, $data['password']);

        $user->setRoles($data['roles']);
        return $user;
    }

    private function extract(User $user): array
    {
        $data = [];
        $data['id'] = $user->id;
        $data['email'] = $user->email;
        $data['admin'] = $user->admin;

        $reflectionClass = new ReflectionClass(User::class);
        $reflectionProperty = $reflectionClass->getProperty('password');
        $reflectionProperty->setAccessible(true);
        $password = $reflectionProperty->getValue($user);
        $data['password'] = $password;

        $reflectionProperty = $reflectionClass->getProperty('roles');
        $reflectionProperty->setAccessible(true);
        $roles = $reflectionProperty->getValue($user);
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