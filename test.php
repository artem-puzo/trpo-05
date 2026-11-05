<?php
require_once __DIR__ . '/src/models/Product.php';

$p = new Product(1, 'Тест', 100.5, 2);
echo $p->getName(), ' ', $p->getPrice(), PHP_EOL;