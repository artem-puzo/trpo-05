<?php
require_once __DIR__ . '/src/services/CatalogService.php';

$service = new CatalogService(
    __DIR__ . '/data/dataset/products.json',
    __DIR__ . '/data/dataset/categories.json'
);

$products = $service->loadProducts();
$categories = $service->loadCategories();

echo 'Товаров: ', count($products), PHP_EOL;
echo 'Категорий: ', count($categories), PHP_EOL;

foreach ($products as $product) {
    echo $product->getId(), ' ', $product->getName(), ' ',
         $product->formattedPrice(), ' ',
         $product->isAvailable() ? 'доступен' : 'скрыт', PHP_EOL;
}