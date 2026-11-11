<?php

declare(strict_types=1);

use Ses\Session\Auth;

/**
 * Page heading + tab navigation for the logged-in user area.
 *
 * @var string $heading
 * @var 'consultations'|'devices'|'profile' $activeTab
 * @var array{label: string, href: string}|null $action
 */
$tabs = [
    'consultations' => ['label' => 'Consultations', 'href' => 'dashboard.php'],
    'devices' => ['label' => 'My Devices', 'href' => 'devices.php'],
    'profile' => ['label' => 'Profile', 'href' => 'profile.php'],
];
?>
<section class="container-page pt-6">
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <p class="text-sm text-neutral-500">Welcome back, <?= e(Auth::userName()) ?></p>
            <h1 class="mt-1 text-4xl font-semibold tracking-tight"><?= e($heading) ?></h1>
        </div>
        <?php if (isset($action)): ?>
            <a href="<?= url($action['href']) ?>" class="btn btn-primary">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="M12 5v14M5 12h14"/></svg>
                <?= e($action['label']) ?>
            </a>
        <?php endif; ?>
    </div>

    <!-- Segmented control -->
    <nav class="glass mt-8 inline-flex max-w-full gap-1 overflow-x-auto rounded-full p-1 text-sm">
        <?php foreach ($tabs as $key => $tab): $active = $key === $activeTab; ?>
            <a href="<?= url($tab['href']) ?>"
               class="whitespace-nowrap rounded-full px-5 py-2 transition <?= $active ? 'bg-white text-neutral-900 shadow-sm shadow-neutral-900/10' : 'text-neutral-600 hover:text-neutral-900' ?>">
                <?= $tab['label'] ?>
            </a>
        <?php endforeach; ?>
    </nav>
</section>
