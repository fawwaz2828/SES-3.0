<?php

declare(strict_types=1);

$finder = PhpCsFixer\Finder::create()
    ->in([__DIR__ . '/src', __DIR__ . '/includes', __DIR__ . '/admin'])
    ->append(glob(__DIR__ . '/*.php') ?: [])
    ->notPath('config.local.php');

return (new PhpCsFixer\Config())
    ->setRiskyAllowed(true)
    ->setRules([
        '@PER-CS' => true,
        '@PER-CS:risky' => true,
        'declare_strict_types' => true,
    ])
    ->setFinder($finder);
