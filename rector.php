<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

// Always run with --dry-run first: vendor/bin/rector process --dry-run
return RectorConfig::configure()
    ->withPaths([__DIR__ . '/src', __DIR__ . '/includes', __DIR__ . '/admin'])
    ->withRootFiles()
    ->withSkip([__DIR__ . '/includes/config.local.php'])
    ->withPhpSets(php82: true)
    ->withPreparedSets(deadCode: true, codeQuality: true, typeDeclarations: true);
