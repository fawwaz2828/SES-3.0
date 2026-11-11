<?php

declare(strict_types=1);

namespace Ses\Session;

/**
 * Per-session token that every POST form must send back.
 */
final class Csrf
{
    private const KEY = 'csrf';

    public static function token(): string
    {
        $token = $_SESSION[self::KEY] ?? null;
        if (!\is_string($token) || $token === '') {
            $token = bin2hex(random_bytes(32));
            $_SESSION[self::KEY] = $token;
        }

        return $token;
    }

    public static function field(): string
    {
        return '<input type="hidden" name="_token" value="' . self::token() . '">';
    }

    /** Stops the request when the token is missing or wrong. */
    public static function verify(): void
    {
        $sent = $_POST['_token'] ?? '';
        if (!\is_string($sent) || !hash_equals(self::token(), $sent)) {
            http_response_code(419);
            exit('Your session has expired. Please go back and try again.');
        }
    }
}
