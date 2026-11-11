<?php

declare(strict_types=1);

/**
 * Split-screen layout for login / register.
 *
 * @var string $title
 * @var string $heading
 * @var string $subheading
 * @var string $tagline
 */
require __DIR__ . '/head.php';
require __DIR__ . '/flash.php';
?>
<div class="grid min-h-screen gap-3 p-3 lg:grid-cols-2">
    <aside class="relative hidden flex-col justify-between overflow-hidden rounded-[2.5rem] bg-neutral-950 p-12 text-white lg:flex">
        <img src="<?= url('assets/images/testimonial-bg.png') ?>" alt="" class="absolute inset-0 h-full w-full object-cover opacity-40">
        <a href="<?= url('index.php') ?>" class="relative"><img src="<?= url('assets/images/logo-white.svg') ?>" alt="SES" class="h-7 w-auto"></a>
        <div class="relative max-w-md">
            <h1 class="text-5xl font-semibold leading-tight tracking-tight"><?= e($heading) ?></h1>
            <p class="mt-4 text-xl text-neutral-300"><?= e($subheading) ?></p>
            <p class="mt-6 text-sm text-neutral-400"><?= e($tagline) ?></p>
        </div>
    </aside>

    <div class="flex flex-col px-3 py-6 sm:px-10">
        <a href="<?= url('index.php') ?>" class="px-3 lg:hidden"><img src="<?= url('assets/images/logo-black.svg') ?>" alt="SES" class="h-7 w-auto"></a>
        <div class="glass mx-auto my-auto w-full max-w-md rounded-[2.5rem] p-8 sm:p-10">
