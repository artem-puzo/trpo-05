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

$equipment = $service->filterByCategory($products, 2);
echo 'В категории 2: ', count($equipment), PHP_EOL;
echo 'Средняя цена: ', $service->averagePrice($equipment), PHP_EOL;
echo 'Пустой список: ', $service->averagePrice([]), PHP_EOL;

try {
    new Product(1, '   ', 100.0, 2);
} catch (InvalidArgumentException $e) {
    echo 'Ошибка: ', $e->getMessage(), PHP_EOL;
}

try {
    new Product(1, 'Тест', -5.0, 2);
} catch (InvalidArgumentException $e) {
    echo 'Ошибка: ', $e->getMessage(), PHP_EOL;
}