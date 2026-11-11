<?php

declare(strict_types=1);

namespace Ses\Model;

use Ses\Support\Row;

final readonly class User
{
    public function __construct(
        public int $id,
        public string $name,
        public string $lastName,
        public string $email,
        public ?string $businessName,
        public ?string $phoneNumber,
        public ?string $avatar,
        #[\SensitiveParameter]
        public ?string $passwordHash,
    ) {}

    /**
     * @param array<string, mixed> $row
     */
    public static function fromRow(array $row): self
    {
        return new self(
            id: Row::int($row, 'id_user'),
            name: Row::string($row, 'name'),
            lastName: Row::string($row, 'last_name'),
            email: Row::string($row, 'email'),
            businessName: Row::nullableString($row, 'business_name'),
            phoneNumber: Row::nullableString($row, 'phone_number'),
            avatar: Row::nullableString($row, 'avatar'),
            passwordHash: Row::nullableString($row, 'password'),
        );
    }

    public function fullName(): string
    {
        return trim($this->name . ' ' . $this->lastName);
    }

    public function verifyPassword(#[\SensitiveParameter] string $password): bool
    {
        return $this->passwordHash !== null && password_verify($password, $this->passwordHash);
    }
}
