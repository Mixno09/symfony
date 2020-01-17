<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Product;
use App\Entity\Review;
use App\Entity\User;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\DBAL\Types\Types;
use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use Knp\Component\Pager\Event\Subscriber\Paginate\Callback\CallbackPagination;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\Paginator;
use ReflectionClass;
use Throwable;

class DBALProductRepository implements ProductRepositoryInterface, PaginatorAwareInterface
{
    public const PRODUCT_TABLE = 'products';
    public const REVIEW_TABLE = 'reviews';

    /**
     * @var \Doctrine\DBAL\Connection
     */
    private $connection;
    /**
     * @var \Knp\Component\Pager\Paginator
     */
    private $paginator;

    /**
     * DBALProductRepository constructor.
     * @param \Doctrine\DBAL\Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function save(Product $product): void
    {
        $this->connection->beginTransaction();
        try {
            if ($product->id === 0) {
                $this->insert($product);
            } else {
                $this->update($product);
            }
            $this->persistReviews($product);
        } catch (Throwable $exception) {
            $this->connection->rollBack();
            throw $exception;
        }
        $this->connection->commit();
    }

    private function insert(Product $product): void
    {
        $this->connection->insert(
            self::PRODUCT_TABLE,
            [
                'title' => $product->title,
                'description' => $product->description,
                'image' => $product->image,
            ],
            [
                'title' => Types::STRING,
                'description' => Types::STRING,
                'image' => Types::STRING,
            ]
        );

        $id = (int)$this->connection->lastInsertId();
        $product->id = $id;
    }

    private function update(Product $product): void
    {
        $this->connection->update(
            self::PRODUCT_TABLE,
            [
                'title' => $product->title,
                'description' => $product->description,
                'image' => $product->image,
            ],
            [
                'id' => $product->id,
            ],
            [
                'id' => Types::INTEGER,
                'title' => Types::STRING,
                'description' => Types::STRING,
                'image' => Types::STRING,
            ]
        );
    }

    public function find(int $id): ?Product
    {
        $query = $this->createQuery();
        $query->where('p.id = ' . $query->createNamedParameter($id, Types::INTEGER));
        $data = $this->connection->fetchAll(
            $query->getSQL(),
            $query->getParameters(),
            $query->getParameterTypes()
        );

        $products = $this->hydrate($data);
        $product = reset($products);
        if (! $product instanceof Product) {
            return null;
        }
        return $product;
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

    public function delete(int $id): void
    {
        $this->connection->beginTransaction();

        try {
            $this->deleteReviews([], $id);
            $this->connection->delete(self::PRODUCT_TABLE, ['id' => $id], ['id' => Types::INTEGER]);
        } catch (Throwable $exception) {
            $this->connection->rollBack();
            throw $exception;
        }
        $this->connection->commit();
    }

    /**
     * @param int $count
     * @return \App\Entity\Product[]
     * @throws \Throwable
     */
    public function newest(int $count): array
    {
        $query = $this->createQuery();
        $query
            ->orderBy('id', 'desc')
            ->setMaxResults($count);
        $data = $this->connection->fetchAll(
            $query->getSQL()
        );
        $newest = $this->hydrate($data);
        return $newest;
    }

    /**
     * @inheritDoc
     */
    public function setPaginator(Paginator $paginator)
    {
        $this->paginator = $paginator;
    }

    private function persistReviews(Product $product): void
    {
        $reflectionClass = new ReflectionClass(Product::class);
        $reflectionProperty = $reflectionClass->getProperty('reviews');
        $reflectionProperty->setAccessible(true);
        /** @var \App\Entity\Review[] $reviews */
        $reviews = $reflectionProperty->getValue($product);

        foreach ($reviews as $review) {
            if ($review->id === 0) {
                $this->insertReview($review, $product->id);
            } else {
                $this->updateReview($review);
            }
        }
        $this->deleteReviews($reviews, $product->id);
    }

    private function insertReview(Review $review, int $productId): void
    {
        $this->connection->insert(
            self::REVIEW_TABLE,
            [
                'product_id' => $productId,
                'user_id' => $review->author->id,
                'text' => $review->text,
                'date' => $review->date,
            ],
            [
                'product_id' => Types::INTEGER,
                'user_id' => Types::INTEGER,
                'text' => Types::STRING,
                'date' => Types::DATETIMETZ_IMMUTABLE,
            ]
        );

        $id = (int)$this->connection->lastInsertId();
        $review->id = $id;
    }

    private function updateReview(Review $review): void
    {
        $this->connection->update(
            self::REVIEW_TABLE,
            ['text' => $review->text],
            ['id' => $review->id],
            [
                'text' => Types::STRING,
                'id' => $review->id,
            ]
        );
    }

    /**
     * @param Review[] $reviews
     * @param int $productId
     * @throws \Doctrine\DBAL\DBALException
     */
    private function deleteReviews(array $reviews, int $productId): void
    {
        $reviewId = array_reduce( //создаем массив id-шников отзывов
            $reviews,
            function (array $carry, Review $review) {
                $carry[] = $review->id;
                return $carry;
            },
            []
        );

        $query = $this->connection->createQueryBuilder();
        $query
            ->delete(self::REVIEW_TABLE)
            ->where('product_id = ' . $query->createNamedParameter($productId, Types::INTEGER));

        if (count($reviewId) > 0) {
            $query->andWhere('id NOT IN (' . $query->createNamedParameter($reviewId, Connection::PARAM_INT_ARRAY) . ')');
        }

        $this->connection->executeQuery(
            $query->getSQL(),
            $query->getParameters(),
            $query->getParameterTypes()
        );
    }

    /**
     * @param array $data
     * @return \App\Entity\Product[]
     * @throws \ReflectionException
     */
    private function hydrate(array $data): array
    {
        $products = [];
        foreach ($data as $value) {
            $product = new Product();
            $product->id = $value['id'];
            $product->title = $value['title'];
            $product->description = $value['description'];
            $product->image = $value['image'];
            $products[] = $product;
        }

        $this->loadReviews(...$products);
        return $products;
    }

    /**
     * @param \App\Entity\Product ...$products
     * @throws \ReflectionException
     * @throws \Exception
     */
    private function loadReviews(Product ...$products): void
    {
        if (count($products) === 0) {
            return;
        }

        $productId = array_column($products, 'id');
        $sql =
            'SELECT r.id, r.product_id, r.text, r.date, u.id AS u_id, u.email AS u_email, u.admin AS u_admin, u.roles AS u_roles, u.password AS u_password ' .
            'FROM ' . self::REVIEW_TABLE . ' AS r ' .
            'INNER JOIN ' . PDOUserRepository::TABLE . ' AS u ON r.user_id = u.id ' .
            'WHERE r.product_id IN (:product_id)' .
            'ORDER BY r.date DESC';
        $data = $this->connection->fetchAll($sql, ['product_id' => $productId], ['product_id' => Connection::PARAM_INT_ARRAY]);

        $productReviewsMap = [];
        foreach ($data as $value) {
            $user = $this->hydrateUser($value);
            $review = new Review($user, $value['text']);
            $review->id = $value['id'];
            $review->date = $this->connection->convertToPHPValue($value['date'], Types::DATETIMETZ_IMMUTABLE);
            $productId = $value['product_id'];
            $productReviewsMap[$productId][] = $review;
        }
        foreach ($products as $product) {
            $productId = $product->id;
            if (! array_key_exists($productId, $productReviewsMap)) {
                continue;
            }
            $reviews = $productReviewsMap[$productId];

            $reflectionClass = new ReflectionClass(Product::class);
            $reflectionProperty = $reflectionClass->getProperty('reviews');
            $reflectionProperty->setAccessible(true);
            $reflectionProperty->setValue($product, $reviews);
        }
    }

    private function hydrateUser(array $data): User
    {
        $user = new User();
        $user->id = $data['u_id'];
        $user->email = $data['u_email'];
        $user->admin = $this->connection->convertToPHPValue($data['u_admin'], Types::BOOLEAN);

        $reflectionClass = new ReflectionClass(User::class);
        $reflectionProperty = $reflectionClass->getProperty('password');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($user, $data['u_password']);

        $roles = $this->connection->convertToPHPValue($data['u_roles'], Types::JSON);
        $user->setRoles($roles);
        return $user;
    }

    private function createQuery(): QueryBuilder
    {
        $query = $this->connection->createQueryBuilder();
        $query
            ->select('*')
            ->from(self::PRODUCT_TABLE, 'p');
        return $query;
    }
}
