<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Product;

class FileProductRepository implements ProductRepositoryInterface
{
    /**
     * @var string
     */
    private $file;

    /**
     * FileProductRepository constructor.
     * @param string $file
     */
    public function __construct(string $file)
    {
        if (! is_file($file)) {
            $content = json_encode([]);
            file_put_contents($file, $content);
        }
        $this->file = $file;
    }

    /**
     * @return \App\Entity\Product[]
     */
    private function all(): array
    {
        $content = file_get_contents($this->file);
        $products = json_decode($content);
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
        $content = json_encode($products);
        file_put_contents($this->file, $content);
    }
}