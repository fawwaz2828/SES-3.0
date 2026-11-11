<?php

declare(strict_types=1);

namespace Ses\Dto;

use Ses\Support\Input;

/** Data from the admin "Add product" form. */
final readonly class ProductInput
{
    public function __construct(
        public string $name,
        public string $category,
        public ?float $price,
        public string $description,
    ) {}

    /**
     * @param array<mixed> $input
     */
    public static function fromArray(array $input): self
    {
        return new self(
            name: Input::string($input, 'name', 255),
            category: Input::string($input, 'category', 50),
            price: Input::float($input, 'price'),
            description: Input::string($input, 'description'),
        );
    }

    /**
     * @return list<string>
     */
    public function errors(): array
    {
        return $this->name === '' || $this->category === '' || $this->price === null || $this->price <= 0
            ? ['Name, category and a price above 0 are required.']
            : [];
    }
}
