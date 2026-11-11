<?php

declare(strict_types=1);

use Ses\Enum\ConsultationStatus;
use Ses\Repository\ConsultationRepository;
use Ses\Session\Flash;
use Ses\Support\Input;

require dirname(__DIR__) . '/includes/bootstrap.php';
require_admin();

$consultations = new ConsultationRepository(db());

if (is_post()) {
    $id = Input::int($_POST, 'finish_id');
    if ($id !== null) {
        $consultations->markFinished($id);
        Flash::success('Consultation marked as finished.');
    }
    redirect('admin/consultations.php');
}

$filter = ConsultationStatus::tryFrom(query('status'));
$rows = $consultations->allWithRequester($filter);

$title = 'Consultations';
require dirname(__DIR__) . '/includes/layout/admin-start.php';
?>

<div class="flex flex-wrap items-end justify-between gap-4">
    <div>
        <h1 class="text-2xl font-semibold tracking-tight">Consultations</h1>
        <p class="mt-1 text-sm text-neutral-500">Requests submitted by users.</p>
    </div>
    <div class="flex gap-2">
        <a href="<?= url('admin/consultations.php') ?>" class="pill <?= $filter === null ? 'pill-active' : 'pill-idle' ?>">All</a>
        <?php foreach (ConsultationStatus::cases() as $status): ?>
            <a href="<?= url('admin/consultations.php?status=' . $status->value) ?>" class="pill <?= $filter === $status ? 'pill-active' : 'pill-idle' ?>"><?= $status->label() ?></a>
        <?php endforeach; ?>
    </div>
</div>

<div class="card mt-8 overflow-x-auto">
    <table class="min-w-full text-left text-sm">
        <thead class="border-b border-black/5 text-xs uppercase tracking-wider text-neutral-500">
            <tr>
                <th class="px-6 py-3 font-medium">Company</th>
                <th class="px-6 py-3 font-medium">Requested by</th>
                <th class="px-6 py-3 font-medium">Goals</th>
                <th class="px-6 py-3 font-medium">Budget</th>
                <th class="px-6 py-3 font-medium">Visit date</th>
                <th class="px-6 py-3 font-medium">Status</th>
                <th class="px-6 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-black/5">
            <?php foreach ($rows as $row): $consultation = $row->consultation; ?>
                <tr class="align-top hover:bg-white/50">
                    <td class="px-6 py-4">
                        <p class="font-medium"><?= e($consultation->companyName) ?></p>
                        <p class="text-neutral-500"><?= e($consultation->companyField) ?> · <?= $consultation->companySize ?> people</p>
                        <p class="text-neutral-500"><?= e($consultation->companyAddress) ?></p>
                    </td>
                    <td class="px-6 py-4">
                        <a href="<?= url('admin/user.php?id=' . $consultation->userId) ?>" class="underline-offset-4 hover:underline"><?= e($row->requesterName) ?></a>
                    </td>
                    <td class="max-w-xs px-6 py-4 text-neutral-600"><?= e(excerpt($consultation->goals, 90) ?: '-') ?></td>
                    <td class="whitespace-nowrap px-6 py-4"><?= format_rupiah($consultation->minBudget) ?> – <?= format_rupiah($consultation->maxBudget) ?></td>
                    <td class="whitespace-nowrap px-6 py-4"><?= format_date($consultation->preferredDate) ?></td>
                    <td class="px-6 py-4">
                        <span class="badge <?= $consultation->status->badgeClasses() ?>"><?= $consultation->status->label() ?></span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <?php if ($consultation->status === ConsultationStatus::Running): ?>
                            <form method="POST" onsubmit="return confirm('Mark this consultation as finished?')">
                                <?= csrf_field() ?>
                                <input type="hidden" name="finish_id" value="<?= $consultation->id ?>">
                                <button class="btn btn-primary btn-sm">Mark finished</button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if ($rows === []): ?>
                <tr><td colspan="7" class="px-6 py-10 text-center text-neutral-500">No consultations found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require dirname(__DIR__) . '/includes/layout/admin-end.php'; ?>
