<?php
declare(strict_types=1);

require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../models/Category.php';

final class CatalogService
{
    public function __construct(
        private string $productsPath,
        private string $categoriesPath
    ) {
    }

    public function loadProducts(): array {
        $products = [];
        foreach ($this->readJson($this->productsPath) as $row) {
            $products[] = new Product(
                (int) $row['id'],
                (string) $row['name'],
                (float) $row['price'],
                (int) $row['category_id'],
                (bool) $row['active']
            );
        }
        return $products;
    }

    public function loadCategories(): array {
        $categories = [];
        foreach ($this->readJson($this->categoriesPath) as $row) {
            $categories[] = new Category(
                (int) $row['id'],
                (string) $row['name']
            );
        }
        return $categories;
    }

    public function filterByCategory(array $products, int $categoryId): array {
        return array_values(array_filter(
            $products,
            fn(Product $product) => $product->belongsToCategory($categoryId)
        ));
    }

    public function averagePrice(array $products): float {
        if ($products === []) {
            return 0.0;
        }
        $sum = array_reduce(
            $products,
            fn(float $carry, Product $product) => $carry + $product->getPrice(),
            0.0
        );
        return round($sum / count($products), 2);
    }

    private function readJson(string $path): array {
        if (!is_file($path)) {
            throw new RuntimeException("Файл не найден: $path");
        }
        $data = json_decode(file_get_contents($path), true);
        if (!is_array($data)) {
            throw new RuntimeException("Некорректный JSON в файле: $path");
        }
        return $data;
    }
}