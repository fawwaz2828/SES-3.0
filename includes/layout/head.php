<?php

declare(strict_types=1);

use Ses\App;

/**
 * <head> + opening <body>. Used by every page.
 *
 * @var string|null $title
 * @var string|null $bodyClass
 * @var bool|null $lightsOff  start the page dark until the hero lamp is switched on
 */
$pageTitle = isset($title) ? $title . ' · SES' : 'SES · Sustain Energy Solution';
$cssFile = App::rootDir() . '/assets/css/app.css';
$cssVersion = is_file($cssFile) ? (string) filemtime($cssFile) : '0';
?>
<!DOCTYPE html>
<html lang="en" class="<?= ($lightsOff ?? false) ? 'lights-off' : '' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle) ?></title>
    <link rel="icon" href="<?= url('assets/images/logo-black.svg') ?>">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@200..800&display=swap" rel="stylesheet">

    <!-- Compiled Tailwind CSS (source: assets/css/input.css, build: npm run build:css) -->
    <link rel="stylesheet" href="<?= url('assets/css/app.css?v=' . $cssVersion) ?>">
</head>
<body class="flex min-h-screen flex-col font-sans antialiased <?= e($bodyClass ?? '') ?>">
