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
            $this->requireFields($row, ['id', 'name', 'price', 'category_id'], 'products.json');

            if (!is_numeric($row['id']) || !is_numeric($row['price']) || !is_numeric($row['category_id'])) {
                throw new InvalidArgumentException('В products.json есть нечисловое значение в полях id, price или category_id');
            }

            $products[] = new Product(
                (int) $row['id'],
                (string) $row['name'],
                (float) $row['price'],
                (int) $row['category_id'],
                (bool) ($row['active'] ?? true)
            );
        }
        return $products;
    }

    public function loadCategories(): array {
        $categories = [];
        foreach ($this->readJson($this->categoriesPath) as $row) {
            $this->requireFields($row, ['id', 'name'], 'categories.json');

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

    private function requireFields(mixed $row, array $fields, string $source): void {
        if (!is_array($row)) {
            throw new InvalidArgumentException("В $source строка данных имеет неверный формат");
        }
        foreach ($fields as $field) {
            if (!array_key_exists($field, $row)) {
                throw new InvalidArgumentException("В $source отсутствует поле «$field»");
            }
        }
    }
}