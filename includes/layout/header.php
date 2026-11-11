<?php

declare(strict_types=1);

use Ses\Session\Auth;

/**
 * Page start for the public site and the user area: <head>, floating glass navbar, flash messages.
 *
 * @var string|null $title
 * @var string|null $bodyClass
 * @var bool|null $lightsOff
 * @var bool|null $flushTop  true when the first section sits under the navbar itself (landing hero)
 */
require __DIR__ . '/head.php';

$current = basename(is_string($_SERVER['SCRIPT_NAME'] ?? null) ? $_SERVER['SCRIPT_NAME'] : '');
$loggedIn = Auth::check();
$links = [
    ['label' => 'Home', 'href' => $loggedIn ? 'dashboard.php' : 'index.php', 'match' => ['index.php', 'dashboard.php']],
    ['label' => 'Explore', 'href' => 'explore.php', 'match' => ['explore.php', 'product.php']],
    ['label' => 'Blog', 'href' => 'blog.php', 'match' => ['blog.php', 'article.php']],
];
?>
<header class="fixed inset-x-0 top-0 z-50 px-3 pt-3 sm:px-6 sm:pt-4">
    <nav class="glass mx-auto grid h-14 max-w-6xl grid-cols-[1fr_auto_1fr] items-center rounded-full pl-5 pr-2 transition-colors duration-1000">
        <a href="<?= url($loggedIn ? 'dashboard.php' : 'index.php') ?>" class="justify-self-start" aria-label="SES home">
            <img src="<?= url('assets/images/logo-black.svg') ?>" alt="SES" class="h-6 w-auto dark:hidden">
            <img src="<?= url('assets/images/logo-white.svg') ?>" alt="SES" class="hidden h-6 w-auto dark:block">
        </a>

        <div class="hidden items-center gap-1 md:flex">
            <?php foreach ($links as $link): $active = in_array($current, $link['match'], true); ?>
                <a href="<?= url($link['href']) ?>"
                   class="rounded-full px-4 py-1.5 text-sm transition <?= $active
                       ? 'bg-white/80 text-neutral-900 shadow-sm shadow-neutral-900/10 dark:bg-white/15 dark:text-white'
                       : 'text-neutral-600 hover:bg-white/50 hover:text-neutral-900 dark:text-white/60 dark:hover:bg-white/10 dark:hover:text-white' ?>">
                    <?= $link['label'] ?>
                </a>
            <?php endforeach; ?>
        </div>

        <div class="flex items-center justify-self-end gap-1">
            <div class="hidden items-center gap-1 md:flex">
                <?php if ($loggedIn): ?>
                    <a href="<?= url('profile.php') ?>" class="btn btn-ghost max-w-[10rem] truncate"><?= e(Auth::userName()) ?></a>
                <?php else: ?>
                    <a href="<?= url('login.php') ?>" class="btn btn-ghost">Log in</a>
                <?php endif; ?>
                <a href="<?= url('consultation-form.php') ?>" class="btn btn-primary">Book a Consultation</a>
            </div>

            <button type="button" class="btn btn-ghost h-10 w-10 p-0 md:hidden" data-menu-toggle aria-label="Open menu" aria-expanded="false">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" d="M4 8h16M4 16h16"/>
                </svg>
            </button>
        </div>
    </nav>

    <div class="glass mx-auto mt-2 hidden max-w-6xl rounded-3xl p-3 md:hidden" data-menu>
        <div class="flex flex-col gap-1">
            <?php foreach ($links as $link): ?>
                <a href="<?= url($link['href']) ?>" class="rounded-2xl px-4 py-2.5 text-sm hover:bg-white/60 dark:hover:bg-white/10"><?= $link['label'] ?></a>
            <?php endforeach; ?>
            <?php if ($loggedIn): ?>
                <a href="<?= url('profile.php') ?>" class="rounded-2xl px-4 py-2.5 text-sm hover:bg-white/60 dark:hover:bg-white/10">My account</a>
                <a href="<?= url('logout.php') ?>" class="rounded-2xl px-4 py-2.5 text-sm text-red-600 hover:bg-white/60">Log out</a>
            <?php else: ?>
                <a href="<?= url('login.php') ?>" class="rounded-2xl px-4 py-2.5 text-sm hover:bg-white/60 dark:hover:bg-white/10">Log in</a>
            <?php endif; ?>
            <a href="<?= url('consultation-form.php') ?>" class="btn btn-primary mt-2">Book a Consultation</a>
        </div>
    </div>
</header>

<script>
    document.querySelector('[data-menu-toggle]').addEventListener('click', (event) => {
        const open = document.querySelector('[data-menu]').classList.toggle('hidden') === false;
        event.currentTarget.setAttribute('aria-expanded', String(open));
    });
</script>

<?php require __DIR__ . '/flash.php'; ?>

<main class="flex-1 <?= ($flushTop ?? false) ? '' : 'pt-24 sm:pt-28' ?>">
