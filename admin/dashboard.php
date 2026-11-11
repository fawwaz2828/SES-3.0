<?php

declare(strict_types=1);

use Ses\Enum\ConsultationStatus;
use Ses\Repository\ArticleRepository;
use Ses\Repository\ConsultationRepository;
use Ses\Repository\ProductRepository;
use Ses\Repository\UserRepository;

require dirname(__DIR__) . '/includes/bootstrap.php';
$adminName = require_admin();

$consultations = new ConsultationRepository(db());
$stats = [
    'Users' => (new UserRepository(db()))->count(),
    'Running consultations' => $consultations->countByStatus(ConsultationStatus::Running),
    'Products' => (new ProductRepository(db()))->count(),
    'Blog posts' => (new ArticleRepository(db()))->count(),
];
$recent = $consultations->latest(5);

$title = 'Admin dashboard';
require dirname(__DIR__) . '/includes/layout/admin-start.php';
?>

<h1 class="text-2xl font-semibold tracking-tight">Welcome back, <?= e($adminName) ?></h1>
<p class="mt-1 text-sm text-neutral-500">Here's what's happening on SES.</p>

<div class="mt-8 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
    <?php foreach ($stats as $label => $value): ?>
        <div class="card p-5">
            <p class="text-sm text-neutral-500"><?= $label ?></p>
            <p class="mt-2 text-3xl font-semibold"><?= number_format($value) ?></p>
        </div>
    <?php endforeach; ?>
</div>

<section class="card mt-8">
    <header class="flex items-center justify-between border-b border-black/5 px-6 py-4">
        <h2 class="font-semibold">Latest consultations</h2>
        <a href="<?= url('admin/consultations.php') ?>" class="text-sm text-neutral-500 hover:text-neutral-900">View all →</a>
    </header>
    <?php if ($recent !== []): ?>
        <ul class="divide-y divide-black/5">
            <?php foreach ($recent as $consultation): ?>
                <li class="flex items-center justify-between gap-4 px-6 py-4 text-sm">
                    <div class="min-w-0">
                        <p class="truncate font-medium"><?= e($consultation->companyName) ?></p>
                        <p class="truncate text-neutral-500"><?= e($consultation->companyField) ?></p>
                    </div>
                    <div class="flex shrink-0 items-center gap-4">
                        <span class="text-neutral-500"><?= format_date($consultation->createdAt) ?></span>
                        <span class="badge <?= $consultation->status->badgeClasses() ?>"><?= $consultation->status->label() ?></span>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p class="px-6 py-10 text-center text-sm text-neutral-500">No consultations yet.</p>
    <?php endif; ?>
</section>

<?php require dirname(__DIR__) . '/includes/layout/admin-end.php'; ?>
