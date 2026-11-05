<?php
declare(strict_types=1);

require_once __DIR__ . '/../src/services/CatalogService.php';

function e(string $value): string {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

$error = null;
$products = [];
$categories = [];
$categoryNames = [];
$selectedCategory = null;
$average = 0.0;

try {
    $service = new CatalogService(
        __DIR__ . '/../data/dataset/products.json',
        __DIR__ . '/../data/dataset/categories.json'
    );
    $categories = $service->loadCategories();
    $products = $service->loadProducts();

    foreach ($categories as $category) {
        $categoryNames[$category->getId()] = $category->getName();
    }

    $raw = $_GET['category'] ?? '';
    if ($raw !== '') {
        $id = filter_var($raw, FILTER_VALIDATE_INT);
        if ($id !== false && isset($categoryNames[$id])) {
            $selectedCategory = $id;
            $products = $service->filterByCategory($products, $id);
        } else {
            $error = 'Неизвестная категория, показан весь каталог.';
        }
    }

    $average = $service->averagePrice($products);
} catch (RuntimeException | InvalidArgumentException $exception) {
    error_log($exception->getMessage());
    $error = 'Не удалось загрузить каталог. Проверьте файлы данных.';
}
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Каталог товаров</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header>
    <div class="brand">Практическая работа 5</div>
    <span>Абдрахманов</span>
</header>

<main class="wrap">
    <h1>Каталог товаров</h1>

    <?php if ($error !== null): ?>
        <p class="alert"><?= e($error) ?></p>
    <?php endif; ?>

    <form method="get" class="filter">
        <label for="category">Категория</label>
        <select name="category" id="category">
            <option value="">Все категории</option>
            <?php foreach ($categories as $category): ?>
                <option value="<?= $category->getId() ?>"
                    <?= $selectedCategory === $category->getId() ? 'selected' : '' ?>>
                    <?= e($category->getName()) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Показать</button>
    </form>

    <p class="summary">
        Найдено: <?= count($products) ?>.
        Средняя цена: <?= e(number_format($average, 2, ',', ' ')) ?> ₽
    </p>

    <?php if ($products === []): ?>
        <p class="empty">Товары не найдены.</p>
    <?php else: ?>
        <section class="grid">
            <?php foreach ($products as $product): ?>
                <article class="card">
                    <h2><?= e($product->getName()) ?></h2>
                    <p class="muted"><?= e($categoryNames[$product->getCategoryId()] ?? 'Без категории') ?></p>
                    <p class="price"><?= e($product->formattedPrice()) ?></p>
                    <?php if ($product->isAvailable()): ?>
                        <span class="pill">в наличии</span>
                    <?php else: ?>
                        <span class="pill pill--off">недоступен</span>
                    <?php endif; ?>
                </article>
            <?php endforeach; ?>
        </section>
    <?php endif; ?>
</main>
</body>
</html>