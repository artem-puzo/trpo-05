<?php
declare(strict_types=1);

final class Category
{
    public function __construct(
        private int $id,
        private string $name
    ) {
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