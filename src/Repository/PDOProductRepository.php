<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Product;
use App\Entity\Review;
use App\Entity\User;
use DateTimeImmutable;
use DateTimeZone;
use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use Knp\Component\Pager\Event\Subscriber\Paginate\Callback\CallbackPagination;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\Paginator;
use PDO;
use ReflectionClass;

class PDOProductRepository implements ProductRepositoryInterface, PaginatorAwareInterface
{
    public const PRODUCT_TABLE = 'products';
    public const REVIEW_TABLE = 'reviews';

    /**
     * @var \PDO
     */
    private $pdo;
    /**
     * @var \Knp\Component\Pager\Paginator
     */
    private $paginator;

    /**
     * PDOProductRepository constructor.
     * @param \PDO $pdo
     */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function save(Product $product): void
    {
        if ($product->id === 0) {
            $this->insert($product);
        } else {
            $this->update($product);
        }
        $this->persistReviews($product);
    }

    private function insert(Product $product): void
    {
        $sql = 'INSERT INTO ' . self::PRODUCT_TABLE . ' (title, description, image) VALUES (:title, :description, :image)';
        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(':title', $product->title, PDO::PARAM_STR);
        $statement->bindValue(':description', $product->description, PDO::PARAM_STR);
        $statement->bindValue(':image', $product->image, PDO::PARAM_STR);
        $statement->execute();

        $id = (int) $this->pdo->lastInsertId();
        $product->id = $id;
    }

    private function update(Product $product): void
    {
        $sql = 'UPDATE ' . self::PRODUCT_TABLE . ' SET title = :title, description = :description, image = :image WHERE id = :id';
        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(':id', $product->id, PDO::PARAM_INT);
        $statement->bindValue(':title', $product->title, PDO::PARAM_STR);
        $statement->bindValue(':description', $product->description, PDO::PARAM_STR);
        $statement->bindValue(':image', $product->image, PDO::PARAM_STR);
        $statement->execute();
    }

    public function find(int $id): ?Product
    {
        $sql = 'SELECT * FROM ' . self::PRODUCT_TABLE . ' WHERE id = :id';
        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(':id', $id, PDO::PARAM_INT);
        $statement->execute();
        $product = $statement->fetchObject(Product::class);
        if (! $product instanceof Product) {
            return null;
        }
        $this->loadReviews($product);
        return $product;
    }

    public function paginate(int $page = 1, int $limit = 10, array $options = []): PaginationInterface
    {
        $count = function () {
            $sql = 'SELECT count(*) FROM ' . self::PRODUCT_TABLE;
            $statement = $this->pdo->query($sql);
            $count = $statement->fetch(PDO::FETCH_COLUMN);
            return $count;
        };
        $items = function ($offset, $limit) {
            $sql = 'SELECT * FROM ' . self::PRODUCT_TABLE . ' ORDER BY id ASC LIMIT ' . $limit . ' OFFSET ' . $offset;
            $statement = $this->pdo->query($sql);
            $items = $statement->fetchAll(PDO::FETCH_CLASS, Product::class);
            $this->loadReviews(...$items);
            return $items;
        };
        $target = new CallbackPagination($count, $items);
        $pagination = $this->paginator->paginate($target, $page, $limit);
        return $pagination;
    }

    public function delete(int $id): void
    {
        $sql = 'DELETE FROM ' . self::PRODUCT_TABLE . ' WHERE id = :id';
        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(':id', $id, PDO::PARAM_INT);
        $statement->execute();
    }

    /**
     * @return \App\Entity\Product[]
     */
    public function newest(int $count): array
    {
        $sql = 'SELECT * FROM ' . self::PRODUCT_TABLE . ' ORDER BY id DESC LIMIT ' . $count;
        $statement = $this->pdo->query($sql);
        $newest = $statement->fetchAll(PDO::FETCH_CLASS, Product::class);
        $this->loadReviews(...$newest);
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
        $sql = 'INSERT INTO ' . self::REVIEW_TABLE . ' (product_id, user_id, `text`, `date`) VALUES (:product_id, :user_id, :text, :date)';
        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(':product_id', $productId, PDO::PARAM_INT);
        $statement->bindValue(':user_id', $review->author->id, PDO::PARAM_INT);
        $statement->bindValue(':text', $review->text, PDO::PARAM_STR);
        $date = $review->date->setTimezone(new DateTimeZone('UTC'));
        $statement->bindValue(':date', $date->format('Y-m-d H:i:s'), PDO::PARAM_STR);
        $statement->execute();

        $id = (int) $this->pdo->lastInsertId();
        $review->id = $id;
    }

    private function updateReview(Review $review): void
    {
        $sql = 'UPDATE ' . self::REVIEW_TABLE . ' SET `text` = :text WHERE id = :id';
        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(':text', $review->text, PDO::PARAM_STR);
        $statement->bindValue(':id', $review->id, PDO::PARAM_INT);
        $statement->execute();
    }

    /**
     * @param Review[] $reviews
     * @param int $productId
     */
    private function deleteReviews(array $reviews, int $productId): void
    {
        $reviewId = array_reduce(
            $reviews,
            function (array $carry, Review $review) {
                $carry[] = $review->id;
                return $carry;
            },
            []
        );

        $sql = 'DELETE FROM ' . self::REVIEW_TABLE . ' WHERE product_id = :product_id';
        if (count($reviewId) > 0) {
            $sql .= ' AND id NOT IN (' . implode(',', $reviewId) . ')';
        }
        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(':product_id', $productId, PDO::PARAM_INT);
        $statement->execute();
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
            'WHERE r.product_id IN (' . implode(',', $productId) . ')' .
            'ORDER BY r.date DESC';
        $statement = $this->pdo->query($sql);
        $data = $statement->fetchAll(PDO::FETCH_ASSOC);

        $productReviewsMap = [];
        foreach ($data as $value) {
            $user = $this->hydrateUser($value);
            $review = new Review($user, $value['text']);
            $review->id = $value['id'];
            $date = new DateTimeImmutable($value['date'], new DateTimeZone('UTC'));
            $review->date = $date->setTimezone(new DateTimeZone(date_default_timezone_get()));
            $productId = $value['product_id'];
            $productReviewsMap[$productId][] = $review;
        }
        foreach ($products as $product) {
            $productId = $product->id;
            $reviews = [];
            if (array_key_exists($productId, $productReviewsMap)) {
                $reviews = $productReviewsMap[$productId];
            }
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
        $user->admin = ($data['u_admin'] === 1);

        $reflectionClass = new ReflectionClass(User::class);
        $reflectionProperty = $reflectionClass->getProperty('password');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($user, $data['u_password']);

        $roles = json_decode($data['u_roles']);
        $user->setRoles($roles);
        return $user;
    }
}