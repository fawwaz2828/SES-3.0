<?php

declare(strict_types=1);

namespace Ses\Model;

use Ses\Support\Row;

final readonly class Admin
{
    public function __construct(
        public string $name,
        public string $email,
        #[\SensitiveParameter]
        private string $password,
    ) {}

    /**
     * @param array<string, mixed> $row
     */
    public static function fromRow(array $row): self
    {
        return new self(
            name: Row::string($row, 'nama'),
            email: Row::string($row, 'email'),
            password: Row::string($row, 'password'),
        );
    }

    /**
     * The current schema stores admin passwords as plain text (varchar(20));
     * password hashes are accepted too, so the column can be migrated later.
     */
    public function verifyPassword(#[\SensitiveParameter] string $password): bool
    {
        return password_verify($password, $this->password) || hash_equals($this->password, $password);
    }
}
