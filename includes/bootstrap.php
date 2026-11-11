<?php

declare(strict_types=1);

/**
 * Loaded at the top of every page: autoloader, session, database, CSRF check.
 */

$root = dirname(__DIR__);

if (is_file($root . '/vendor/autoload.php')) {
    // Composer autoloader (also loads includes/helpers.php, see composer.json).
    require $root . '/vendor/autoload.php';
} else {
    // Fallback PSR-4 autoloader so the site also runs without `composer install`.
    spl_autoload_register(static function (string $class) use ($root): void {
        $prefix = 'Ses\\';
        if (str_starts_with($class, $prefix)) {
            $file = $root . '/src/' . str_replace('\\', '/', substr($class, strlen($prefix))) . '.php';
            if (is_file($file)) {
                require $file;
            }
        }
    });
    require __DIR__ . '/helpers.php';
}

Ses\App::boot($root);
