<?php

declare(strict_types=1);

use Ses\Session\Auth;

/**
 * Admin layout: sidebar + main column.
 *
 * @var string $title
 */
require __DIR__ . '/head.php';
require __DIR__ . '/flash.php';

$current = basename(is_string($_SERVER['SCRIPT_NAME'] ?? null) ? $_SERVER['SCRIPT_NAME'] : '');
$menu = [
    ['label' => 'Dashboard', 'href' => 'admin/dashboard.php', 'match' => ['dashboard.php'],
     'icon' => 'M3 12 12 3l9 9M5 10v10h5v-6h4v6h5V10'],
    ['label' => 'Consultations', 'href' => 'admin/consultations.php', 'match' => ['consultations.php', 'user.php'],
     'icon' => 'M8 7V3m8 4V3M4 11h16M5 5h14a1 1 0 0 1 1 1v14a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1Z'],
    ['label' => 'Products', 'href' => 'admin/products.php', 'match' => ['products.php'],
     'icon' => 'M20 7 12 3 4 7m16 0-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4'],
    ['label' => 'Blog', 'href' => 'admin/blogs.php', 'match' => ['blogs.php'],
     'icon' => 'M19 20H5a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h10l6 6v8a2 2 0 0 1-2 2ZM7 9h5m-5 4h10m-10 4h10'],
];
?>
<div class="flex min-h-screen">
    <aside class="glass-dark fixed bottom-3 left-3 top-3 z-40 hidden w-60 flex-col rounded-[2rem] text-white/60 lg:flex">
        <div class="flex h-16 items-center gap-3 border-b border-white/10 px-6">
            <img src="<?= url('assets/images/logo-white.svg') ?>" alt="SES" class="h-6 w-auto">
            <span class="badge bg-white/10 text-white/80">Admin</span>
        </div>
        <nav class="flex-1 space-y-1 p-3">
            <?php foreach ($menu as $item): $active = in_array($current, $item['match'], true); ?>
                <a href="<?= url($item['href']) ?>"
                   class="flex items-center gap-3 rounded-2xl px-3 py-2.5 text-sm transition <?= $active ? 'bg-white/15 text-white shadow-inner shadow-white/5' : 'hover:bg-white/10 hover:text-white' ?>">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="<?= $item['icon'] ?>"/></svg>
                    <?= $item['label'] ?>
                </a>
            <?php endforeach; ?>
        </nav>
        <div class="border-t border-white/10 p-3">
            <p class="px-3 text-xs text-white/40">Signed in as</p>
            <p class="truncate px-3 text-sm text-white"><?= e(Auth::adminName()) ?></p>
            <a href="<?= url('admin/logout.php') ?>" class="mt-3 flex items-center gap-3 rounded-2xl px-3 py-2.5 text-sm hover:bg-white/10 hover:text-white">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17l5-5-5-5m5 5H9m4 9H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h8"/></svg>
                Log out
            </a>
        </div>
    </aside>

    <div class="flex min-w-0 flex-1 flex-col lg:pl-[16.5rem]">
        <!-- Mobile top bar -->
        <header class="glass sticky top-3 z-30 mx-3 mt-3 flex h-12 items-center gap-4 overflow-x-auto rounded-full px-5 text-sm lg:hidden">
            <?php foreach ($menu as $item): ?>
                <a href="<?= url($item['href']) ?>" class="whitespace-nowrap <?= in_array($current, $item['match'], true) ? 'font-semibold' : 'text-neutral-500' ?>"><?= $item['label'] ?></a>
            <?php endforeach; ?>
            <a href="<?= url('admin/logout.php') ?>" class="ml-auto whitespace-nowrap text-red-600">Log out</a>
        </header>

        <main class="mx-auto w-full max-w-6xl flex-1 px-4 py-8 sm:px-8">
