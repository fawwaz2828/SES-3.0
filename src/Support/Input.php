<?php

declare(strict_types=1);

namespace Ses\Support;

use DateTimeImmutable;

/**
 * Typed, trimmed values from request arrays ($_POST, $_GET).
 */
final class Input
{
    /**
     * @param array<mixed> $input
     */
    public static function string(array $input, string $key, int $maxLength = 65535): string
    {
        $value = $input[$key] ?? '';

        return \is_string($value) ? mb_substr(trim($value), 0, $maxLength) : '';
    }

    /**
     * @param array<mixed> $input
     */
    public static function int(array $input, string $key): ?int
    {
        $value = self::string($input, $key);

        return preg_match('/\A-?\d{1,9}\z/', $value) === 1 ? (int) $value : null;
    }

    /**
     * @param array<mixed> $input
     */
    public static function float(array $input, string $key): ?float
    {
        $value = self::string($input, $key);

        return is_numeric($value) ? (float) $value : null;
    }

    /**
     * True when a checkbox (or any non-empty field) was sent.
     *
     * @param array<mixed> $input
     */
    public static function bool(array $input, string $key): bool
    {
        return isset($input[$key]) && $input[$key] !== '';
    }

    /**
     * A real calendar date in Y-m-d format, or null.
     *
     * @param array<mixed> $input
     */
    public static function date(array $input, string $key): ?DateTimeImmutable
    {
        $date = DateTimeImmutable::createFromFormat('!Y-m-d', self::string($input, $key));

        // createFromFormat() rolls 2026-02-30 over to March instead of failing.
        $errors = DateTimeImmutable::getLastErrors();
        if ($date === false || ($errors !== false && ($errors['warning_count'] > 0 || $errors['error_count'] > 0))) {
            return null;
        }

        return $date;
    }
}
