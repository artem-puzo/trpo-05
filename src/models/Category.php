<?php
declare(strict_types=1);

final class Category
{
    public function __construct(
        private int $id,
        private string $name
    ) {
        if ($id <= 0) {
            throw new InvalidArgumentException('ID категории должен быть положительным числом');
        }
        if (trim($name) === '') {
            throw new InvalidArgumentException('Название категории не может быть пустым');
        }
    }

    public function getId(): int {
        return $this->id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function contains(Product $product): bool {
        return $product->belongsToCategory($this->id);
    }
}