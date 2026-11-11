<?php

declare(strict_types=1);

/**
 * Default application settings.
 *
 * To use different values on your machine (e.g. a MySQL password),
 * create includes/config.local.php that returns only the keys you want
 * to override. That file is ignored by git.
 *
 *   <?php return ['db_pass' => '141414'];
 */
$config = [
    'db_host' => 'localhost',
    'db_user' => 'root',
    'db_pass' => '',
    'db_name' => 'ses',
];

$local = __DIR__ . '/config.local.php';
if (is_file($local)) {
    $overrides = require $local;
    if (is_array($overrides)) {
        $config = array_merge($config, $overrides);
    }
}

return $config;
