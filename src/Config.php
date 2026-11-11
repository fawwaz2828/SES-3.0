<?php

declare(strict_types=1);

namespace Ses;

use UnexpectedValueException;

/**
 * Application settings, read from includes/config.php.
 */
final readonly class Config
{
    public function __construct(
        public string $dbHost,
        public string $dbUser,
        #[\SensitiveParameter]
        public string $dbPassword,
        public string $dbName,
    ) {}

    /**
     * @param array<mixed> $values
     */
    public static function fromArray(array $values): self
    {
        return new self(
            dbHost: self::string($values, 'db_host'),
            dbUser: self::string($values, 'db_user'),
            dbPassword: self::string($values, 'db_pass'),
            dbName: self::string($values, 'db_name'),
        );
    }

    /**
     * @param array<mixed> $values
     */
    private static function string(array $values, string $key): string
    {
        $value = $values[$key] ?? null;
        if (!\is_string($value)) {
            throw new UnexpectedValueException(\sprintf('Config value "%s" must be a string.', $key));
        }

        return $value;
    }
}
