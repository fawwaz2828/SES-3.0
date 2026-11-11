<?php

declare(strict_types=1);

namespace Ses;

use LogicException;
use mysqli_sql_exception;
use Ses\Session\Csrf;

/**
 * Boots the application once per request and holds shared services.
 */
final class App
{
    private static ?Database $database = null;

    private static string $rootDir = '';

    private static string $baseUrl = '';

    public static function boot(string $rootDir): void
    {
        self::$rootDir = $rootDir;
        self::$baseUrl = self::detectBaseUrl();

        session_start();

        $config = require $rootDir . '/includes/config.php';
        if (!\is_array($config)) {
            throw new LogicException('includes/config.php must return an array.');
        }

        try {
            self::$database = Database::connect(Config::fromArray($config));
        } catch (mysqli_sql_exception $e) {
            http_response_code(500);
            exit('Database connection failed: ' . htmlspecialchars($e->getMessage()) . '. Check includes/config.php.');
        }

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
            Csrf::verify();
        }
    }

    public static function db(): Database
    {
        return self::$database ?? throw new LogicException('App::boot() has not been called.');
    }

    public static function rootDir(): string
    {
        return self::$rootDir;
    }

    /** URL path of the project root, e.g. "" or "/SES-4.0". */
    public static function baseUrl(): string
    {
        return self::$baseUrl;
    }

    /** Works for pages in the project root and in /admin. */
    private static function detectBaseUrl(): string
    {
        $script = $_SERVER['SCRIPT_NAME'] ?? '';
        $dir = str_replace('\\', '/', \dirname(\is_string($script) ? $script : ''));

        return rtrim((string) preg_replace('#/admin$#', '', $dir), '/');
    }
}
