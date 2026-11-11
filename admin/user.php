<?php

declare(strict_types=1);

use Ses\Repository\ConsultationRepository;
use Ses\Repository\DeviceRepository;
use Ses\Repository\UserRepository;
use Ses\Session\Flash;

require dirname(__DIR__) . '/includes/bootstrap.php';
require_admin();

$user = (new UserRepository(db()))->find((int) query('id'));
if ($user === null) {
    Flash::error('User not found.');
    redirect('admin/consultations.php');
}
$consultations = (new ConsultationRepository(db()))->forUser($user->id);
$deviceCount = (new DeviceRepository(db()))->countForUser($user->id);

$details = [
    'Business' => $user->businessName,
    'Phone' => $user->phoneNumber,
    'Devices' => (string) $deviceCount,
];

$title = 'User profile';
require dirname(__DIR__) . '/includes/layout/admin-start.php';
?>

<a href="<?= url('admin/consultations.php') ?>" class="text-sm text-neutral-500 hover:text-neutral-900">← Back to consultations</a>

<div class="mt-6 grid gap-6 lg:grid-cols-[20rem_1fr]">
    <section class="card self-start p-6 text-center">
        <img src="<?= avatar_url($user->avatar) ?>" alt="Avatar" class="mx-auto h-24 w-24 rounded-full object-cover ring-4 ring-white/80">
        <h1 class="mt-4 text-xl font-semibold"><?= e($user->fullName()) ?></h1>
        <p class="text-sm text-neutral-500"><?= e($user->email) ?></p>
        <dl class="mt-6 space-y-3 border-t border-black/5 pt-6 text-left text-sm">
            <?php foreach ($details as $label => $value): ?>
                <div class="flex justify-between gap-4"><dt class="text-neutral-500"><?= $label ?></dt><dd><?= e($value !== null && $value !== '' ? $value : '-') ?></dd></div>
            <?php endforeach; ?>
        </dl>
    </section>

    <section class="card self-start">
        <h2 class="border-b border-black/5 px-6 py-4 font-semibold">Consultations</h2>
        <ul class="divide-y divide-black/5 text-sm">
            <?php foreach ($consultations as $consultation): ?>
                <li class="flex items-center justify-between gap-4 px-6 py-4">
                    <span class="font-medium"><?= e($consultation->companyName) ?></span>
                    <span class="flex items-center gap-4">
                        <span class="text-neutral-500"><?= format_date($consultation->createdAt) ?></span>
                        <span class="badge <?= $consultation->status->badgeClasses() ?>"><?= $consultation->status->label() ?></span>
                    </span>
                </li>
            <?php endforeach; ?>
            <?php if ($consultations === []): ?>
                <li class="px-6 py-10 text-center text-neutral-500">No consultations.</li>
            <?php endif; ?>
        </ul>
    </section>
</div>

<?php require dirname(__DIR__) . '/includes/layout/admin-end.php'; ?>
