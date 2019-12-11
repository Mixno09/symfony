<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Product;
use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use Knp\Component\Pager\Event\Subscriber\Paginate\Callback\CallbackPagination;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\Paginator;
use PDO;

class PDOProductRepository implements ProductRepositoryInterface, PaginatorAwareInterface
{
    public const TABLE = 'products';

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
    }

    private function insert(Product $product): void
    {
        $sql = 'INSERT INTO ' . self::TABLE . ' (title, description, image) VALUES (:title, :description, :image)';
        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(':title', $product->title, PDO::PARAM_STR);
        $statement->bindValue(':description', $product->description, PDO::PARAM_STR);
        $statement->bindValue(':image', $product->image, PDO::PARAM_STR);
        $statement->execute();

        $id = $this->pdo->lastInsertId();
        $product->id = $id;
    }

    private function update(Product $product): void
    {
        $sql = 'UPDATE ' . self::TABLE . ' SET title = :title, description = :description, image = :image WHERE id = :id';
        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(':id', $product->id, PDO::PARAM_INT);
        $statement->bindValue(':title', $product->title, PDO::PARAM_STR);
        $statement->bindValue(':description', $product->description, PDO::PARAM_STR);
        $statement->bindValue(':image', $product->image, PDO::PARAM_STR);
        $statement->execute();
    }

    public function find(int $id): ?Product
    {
        $sql = 'SELECT * FROM ' . self::TABLE . ' WHERE id = :id';
        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(':id', $id, PDO::PARAM_INT);
        $statement->execute();
        $product = $statement->fetchObject(Product::class);
        if (! $product instanceof Product) {
            $product = null;
        }
        return $product;
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
            $items = $statement->fetchAll(PDO::FETCH_CLASS, Product::class);
            return $items;
        };
        $target = new CallbackPagination($count, $items);
        $pagination = $this->paginator->paginate($target, $page, $limit);
        return $pagination;
    }

    public function delete(int $id): void
    {
        $sql = 'DELETE FROM ' . self::TABLE . ' WHERE id = :id';
        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(':id', $id, PDO::PARAM_INT);
        $statement->execute();
    }

    /**
     * @return \App\Entity\Product[]
     */
    public function newest(int $count): array
    {
        $sql = 'SELECT * FROM ' . self::TABLE . ' ORDER BY id DESC LIMIT ' . $count;
        $statement = $this->pdo->query($sql);
        $newest = $statement->fetchAll(PDO::FETCH_CLASS, Product::class);
        return $newest;
    }

    /**
     * @inheritDoc
     */
    public function setPaginator(Paginator $paginator)
    {
        $this->paginator = $paginator;
    }
}