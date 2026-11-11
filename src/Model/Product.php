<?php

declare(strict_types=1);

namespace Ses\Model;

use Ses\Support\Row;

final readonly class Product
{
    public function __construct(
        public int $id,
        public string $name,
        public string $category,
        public float $price,
        public ?string $description,
        public ?string $image,
    ) {}

    /**
     * @param array<string, mixed> $row
     */
    public static function fromRow(array $row): self
    {
        return new self(
            id: Row::int($row, 'id'),
            name: Row::string($row, 'name'),
            category: Row::string($row, 'category'),
            price: Row::float($row, 'price'),
            description: Row::nullableString($row, 'description'),
            image: Row::nullableString($row, 'image'),
        );
    }
}
