<?php

declare(strict_types=1);

use Ses\Enum\ConsultationStatus;
use Ses\Model\Consultation;
use Ses\Repository\ConsultationRepository;

require __DIR__ . '/includes/bootstrap.php';
$userId = require_login();

$consultations = (new ConsultationRepository(db()))->forUser($userId);

$groups = [];
foreach (ConsultationStatus::cases() as $status) {
    $groups[] = [
        'status' => $status,
        'items' => array_values(array_filter($consultations, static fn(Consultation $c): bool => $c->status === $status)),
    ];
}

$title = 'Dashboard';
require __DIR__ . '/includes/layout/header.php';

$heading = 'Consultations';
$activeTab = 'consultations';
$action = ['label' => 'Book a Consultation', 'href' => 'consultation-form.php'];
require __DIR__ . '/includes/layout/user-tabs.php';
?>

<div class="container-page grid gap-6 py-10 lg:grid-cols-2">
    <?php foreach ($groups as $group): ?>
        <section class="card">
            <header class="flex items-center justify-between border-b border-black/5 px-6 py-4">
                <h2 class="font-semibold"><?= $group['status']->label() ?></h2>
                <span class="badge bg-black/5 text-neutral-600"><?= count($group['items']) ?></span>
            </header>

            <?php if ($group['items'] !== []): ?>
                <ul class="divide-y divide-black/5">
                    <?php foreach ($group['items'] as $consultation): ?>
                        <li>
                            <a href="<?= url('consultation.php?id=' . $consultation->id) ?>" class="flex items-center justify-between gap-4 px-6 py-4 transition hover:bg-white/50">
                                <div class="min-w-0">
                                    <p class="truncate font-medium"><?= e($consultation->companyName) ?></p>
                                    <p class="truncate text-sm text-neutral-500"><?= e($consultation->companyField) ?></p>
                                </div>
                                <div class="shrink-0 text-right text-xs text-neutral-500">
                                    <p>Submitted <?= format_date($consultation->createdAt) ?></p>
                                    <p class="mt-0.5">Visit <?= format_date($consultation->preferredDate) ?></p>
                                </div>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p class="px-6 py-10 text-center text-sm text-neutral-500">
                    You don't have any <?= strtolower($group['status']->label()) ?> consultation yet.
                </p>
            <?php endif; ?>
        </section>
    <?php endforeach; ?>
</div>

<?php require __DIR__ . '/includes/layout/footer.php'; ?>
