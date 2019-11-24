<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Product;
use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\Paginator;

class FileProductRepository implements ProductRepositoryInterface, PaginatorAwareInterface
{
    /**
     * @var string
     */
    private $file;
    /**
     * @var \Knp\Component\Pager\Paginator
     */
    private $paginator;

    /**
     * FileProductRepository constructor.
     * @param string $file
     */
    public function __construct(string $file)
    {
        $this->file = $file;
    }

    /**
     * @return \App\Entity\Product[]
     */
    private function all(): array
    {
        if (! is_file($this->file)) {
            return [];
        }
        $content = file_get_contents($this->file);
        $products = unserialize($content);
        return $products;
    }

    public function save(Product $product): void
    {
        $products = $this->all();
        if ($product->id === 0) {
            $id = 0;
            foreach ($products as $value) {
                if ($id < $value->id) {
                    $id = $value->id;
                }
            }
            $product->id = $id + 1;
            $products[] = $product;
        } else {
            foreach ($products as $key => $value) {
                if ($value->id === $product->id) {
                    $products[$key] = $product;
                    break;
                }
            }
        }
        $this->persist($products);
    }

    public function find(int $id): ?Product
    {
        $products = $this->all();
        foreach ($products as $product) {
            if ($product->id === $id) {
                return $product;
            }
        }
        return null;
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
     */
    public function setPaginator(Paginator $paginator)
    {
        $this->paginator = $paginator;
    }

    public function delete(int $id): void
    {
        $products = $this->all();
        $products = array_filter($products, function (Product $product) use ($id) { // лучше делать через foreach, array_filter для наглядности
            return ($product->id !== $id);
        });
        $this->persist($products);
    }

    private function persist(array $products): void
    {
        $content = serialize($products);
        file_put_contents($this->file, $content);
    }

    /**
     * @return \App\Entity\Product[]
     */
    public function newest(int $count): array
    {
        $products = $this->all();
        $products = array_slice($products, -1 * $count);
        $products = array_reverse($products);
        return $products;
    }
}