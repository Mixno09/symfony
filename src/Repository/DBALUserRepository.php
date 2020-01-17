<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;
use Doctrine\Common\Cache\ArrayCache;
use Doctrine\DBAL\Cache\QueryCacheProfile;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\DBAL\Types\Types;
use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use Knp\Component\Pager\Event\Subscriber\Paginate\Callback\CallbackPagination;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\Paginator;
use ReflectionClass;

class DBALUserRepository implements UserRepositoryInterface, PaginatorAwareInterface
{
    public const USER_TABLE = 'users';

    /**
     * @var \Knp\Component\Pager\Paginator
     */
    private $paginator;
    /**
     * @var \Doctrine\DBAL\Connection
     */
    private $connection;
    /**
     * @var \Doctrine\Common\Cache\Cache
     */
    private $cache;

    /**
     * DBALUserRepository constructor.
     * @param \Doctrine\DBAL\Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
        $this->cache = new ArrayCache();
    }

    public function find(int $id): ?User
    {
        $query = $this->createQuery();
        $query->where('u.id = ' . $query->createNamedParameter($id, Types::INTEGER));
        $data = $this->connection->fetchAll(
            $query->getSQL(),
            $query->getParameters(),
            $query->getParameterTypes()
        );
        $users = $this->hydrate($data);
        $user = reset($users);
        if (! $user instanceof User) {
            return null;
        }
        return $user;
    }

    public function getByEmail(string $email): ?User
    {
        $query = $this->createQuery();
        $query->where('u.email = ' . $query->createNamedParameter($email, Types::STRING));

        $statement = $this->connection->executeCacheQuery(
            $query->getSQL(),
            $query->getParameters(),
            $query->getParameterTypes(),
            new QueryCacheProfile(0, $email, $this->cache)
        );
        $data = $statement->fetchAll();
        $statement->closeCursor();

        $users = $this->hydrate($data);
        $user = reset($users);
        if (! $user instanceof User) {
            return null;
        }
        return $user;
    }

    public function paginate(int $page = 1, int $limit = 10, array $options = []): PaginationInterface
    {
        $count = function () {
            $query = $this->createQuery();
            $query->select('count(*)');
            $count = $this->connection->fetchColumn(
                $query->getSQL()
            );
            return $count;
        };
        $items = function ($offset, $limit) {
            $query = $this->createQuery();
            $query
                ->orderBy('id')
                ->setFirstResult($offset)
                ->setMaxResults($limit);
            $data = $this->connection->fetchAll(
                $query->getSQL()
            );

            $items = $this->hydrate($data);
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
            self::USER_TABLE,
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

        $id = (int)$this->connection->lastInsertId();
        $user->id = $id;
    }

    private function update(User $user): void
    {
        $data = $this->extract($user);
        $this->connection->update(
            self::USER_TABLE,
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
        $this->connection->delete(self::USER_TABLE, ['id' => $id], ['id' => Types::INTEGER]);
    }

    /**
     * @param array $data
     * @return \App\Entity\User[]
     * @throws \ReflectionException
     */
    private function hydrate(array $data): array
    {
        $users = [];
        foreach ($data as $value) {
            $user = new User();
            $user->id = $value['id'];
            $user->email = $value['email'];
            $user->admin = $this->connection->convertToPHPValue($value['admin'], Types::BOOLEAN);;

            $reflectionClass = new ReflectionClass(User::class);
            $reflectionProperty = $reflectionClass->getProperty('password');
            $reflectionProperty->setAccessible(true);
            $reflectionProperty->setValue($user, $value['password']);

            $value['roles'] = $this->connection->convertToPHPValue($value['roles'], Types::JSON);
            $user->setRoles($value['roles']);

            $users[] = $user;
        }
        return $users;
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

    private function createQuery(): QueryBuilder
    {
        $query = $this->connection->createQueryBuilder();
        $query
            ->select('*')
            ->from(self::USER_TABLE, 'u');
        return $query;
    }
}
