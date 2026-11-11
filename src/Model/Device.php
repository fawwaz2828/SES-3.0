<?php

declare(strict_types=1);

namespace Ses\Model;

use DateTimeImmutable;
use Ses\Support\Row;

final readonly class Device
{
    public function __construct(
        public int $id,
        public int $userId,
        public string $name,
        public ?DateTimeImmutable $createdAt,
    ) {}

    /**
     * @param array<string, mixed> $row
     */
    public static function fromRow(array $row): self
    {
        return new self(
            id: Row::int($row, 'id'),
            userId: Row::int($row, 'id_user'),
            name: Row::string($row, 'device_name'),
            createdAt: Row::nullableDate($row, 'created_at'),
        );
    }
}
