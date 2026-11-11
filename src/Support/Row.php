<?php

declare(strict_types=1);

namespace Ses\Support;

use DateTimeImmutable;
use UnexpectedValueException;

/**
 * Typed access to columns of a database row.
 */
final class Row
{
    /**
     * @param array<string, mixed> $row
     */
    public static function int(array $row, string $key): int
    {
        return self::nullableInt($row, $key) ?? throw self::invalid($key, 'integer');
    }

    /**
     * @param array<string, mixed> $row
     */
    public static function nullableInt(array $row, string $key): ?int
    {
        $value = $row[$key] ?? null;

        return match (true) {
            $value === null => null,
            \is_int($value) => $value,
            is_numeric($value) => (int) $value,
            default => throw self::invalid($key, 'integer'),
        };
    }

    /**
     * @param array<string, mixed> $row
     */
    public static function float(array $row, string $key): float
    {
        $value = $row[$key] ?? null;

        return is_numeric($value) ? (float) $value : throw self::invalid($key, 'number');
    }

    /**
     * @param array<string, mixed> $row
     */
    public static function string(array $row, string $key): string
    {
        return self::nullableString($row, $key) ?? throw self::invalid($key, 'string');
    }

    /**
     * @param array<string, mixed> $row
     */
    public static function nullableString(array $row, string $key): ?string
    {
        $value = $row[$key] ?? null;

        return match (true) {
            $value === null => null,
            \is_string($value) => $value,
            \is_int($value), \is_float($value) => (string) $value,
            default => throw self::invalid($key, 'string'),
        };
    }

    /**
     * @param array<string, mixed> $row
     */
    public static function bool(array $row, string $key): bool
    {
        return self::int($row, $key) !== 0;
    }

    /**
     * @param array<string, mixed> $row
     */
    public static function date(array $row, string $key): DateTimeImmutable
    {
        return self::nullableDate($row, $key) ?? throw self::invalid($key, 'date');
    }

    /**
     * @param array<string, mixed> $row
     */
    public static function nullableDate(array $row, string $key): ?DateTimeImmutable
    {
        $value = self::nullableString($row, $key);
        if ($value === null || $value === '' || str_starts_with($value, '0000-00-00')) {
            return null;
        }

        try {
            return new DateTimeImmutable($value);
        } catch (\Exception) {
            throw self::invalid($key, 'date');
        }
    }

    private static function invalid(string $key, string $type): UnexpectedValueException
    {
        return new UnexpectedValueException(\sprintf('Column "%s" is not a valid %s.', $key, $type));
    }
}
