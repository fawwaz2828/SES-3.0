<?php

declare(strict_types=1);

use Ses\Repository\ConsultationRepository;
use Ses\Session\Flash;

require __DIR__ . '/includes/bootstrap.php';
$userId = require_login();

// Users can only see their own consultations.
$consultation = (new ConsultationRepository(db()))->findForUser((int) query('id'), $userId);
if ($consultation === null) {
    Flash::error('Consultation not found.');
    redirect('dashboard.php');
}

$sections = [
    'Company detail' => [
        'Company name' => $consultation->companyName,
        'Company field' => $consultation->companyField,
        'Company size' => $consultation->companySize . ' employees',
        'Company address' => $consultation->companyAddress,
    ],
    'Consultation detail' => [
        'Current lighting' => $consultation->currentLighting,
        'Problem detail' => $consultation->problemDetail,
        'Goals' => $consultation->goals,
        'Budget' => format_rupiah($consultation->minBudget) . ' – ' . format_rupiah($consultation->maxBudget),
        'Preferred date' => format_date($consultation->preferredDate),
        'Submitted' => format_date($consultation->createdAt, 'd M Y, H:i'),
    ],
];

$title = 'Consultation detail';
require __DIR__ . '/includes/layout/header.php';
?>

<div class="container-page max-w-3xl py-10">
    <a href="<?= url('dashboard.php') ?>" class="text-sm text-neutral-500 hover:text-neutral-900">← Back to dashboard</a>

    <div class="mt-6 flex flex-wrap items-center justify-between gap-4">
        <h1 class="text-3xl font-semibold tracking-tight"><?= e($consultation->companyName) ?></h1>
        <span class="badge <?= $consultation->status->badgeClasses() ?>"><?= $consultation->status->label() ?></span>
    </div>

    <?php foreach ($sections as $heading => $rows): ?>
        <section class="card mt-8">
            <h2 class="border-b border-black/5 px-6 py-4 font-semibold"><?= $heading ?></h2>
            <dl class="divide-y divide-black/5">
                <?php foreach ($rows as $label => $value): ?>
                    <div class="grid gap-1 px-6 py-4 sm:grid-cols-3 sm:gap-4">
                        <dt class="text-sm text-neutral-500"><?= $label ?></dt>
                        <dd class="whitespace-pre-line text-sm sm:col-span-2"><?= e($value !== null && $value !== '' ? $value : '-') ?></dd>
                    </div>
                <?php endforeach; ?>
            </dl>
        </section>
    <?php endforeach; ?>
</div>

<?php require __DIR__ . '/includes/layout/footer.php'; ?>
