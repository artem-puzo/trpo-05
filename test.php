<?php
require_once __DIR__ . '/src/models/Product.php';
require_once __DIR__ . '/src/models/Category.php';

$p = new Product(1, 'Тест', 100.5, 2);
echo $p->getName(), ' ', $p->getPrice(), PHP_EOL;
echo $p->formattedPrice(), PHP_EOL;
var_dump($p->isAvailable());
var_dump($p->belongsToCategory(2));
echo $p->priceWithDiscount(10), PHP_EOL;


$c = new Category(2, 'Оборудование');
echo $c->getName(), PHP_EOL;
var_dump($c->contains($p));