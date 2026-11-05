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
        if ($id <= 0) {
            throw new InvalidArgumentException('ID товара должен быть положительным числом');
        }
        if (trim($name) === '') {
            throw new InvalidArgumentException('Название товара не может быть пустым');
        }
        if ($price < 0) {
            throw new InvalidArgumentException('Цена товара не может быть отрицательной');
        }
        if ($categoryId <= 0) {
            throw new InvalidArgumentException('ID категории должен быть положительным числом');
        }
    }

    public function formattedPrice(): string {
        return number_format($this->price, 2, ',', ' ') . ' ₽';
    }

    public function isAvailable(): bool {
        return $this->active && $this->price > 0;
    }

    public function belongsToCategory(int $categoryId): bool {
        return $this->categoryId === $categoryId;
    }

    public function priceWithDiscount(float $percent): float {
        if ($percent < 0 || $percent > 100){
            throw new InvalidArgumentException('Скидка должна быть от 0 до 100 процентов');
        } 
        return round($this->price * (1 - $percent / 100), 2);
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