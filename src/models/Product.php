<?php 
declare(strict_types=1);

final class Product{
    public function __construct(
        private int $id,
        private string $name,
        private float $price,
        private int $categoryId,
        private bool $active = true
    ) {

    }

    public function getId(): int {
        return $this->id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getPrice(): float {
        return $this->price;
    }

    public function getCategoryId(): int {
        return $this->categoryId;
    }

    public function isActive(): bool{
        return $this->active;
    }
}