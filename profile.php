<?php

declare(strict_types=1);

use Ses\Repository\UserRepository;

require __DIR__ . '/includes/bootstrap.php';
$userId = require_login();

$user = (new UserRepository(db()))->find($userId) ?? redirect('logout.php');

$details = [
    'Full name' => $user->fullName(),
    'Email' => $user->email,
    'Business name' => $user->businessName,
    'Phone number' => $user->phoneNumber,
];

$title = 'Profile';
require __DIR__ . '/includes/layout/header.php';

$heading = 'Profile';
$activeTab = 'profile';
require __DIR__ . '/includes/layout/user-tabs.php';
?>

<div class="container-page max-w-3xl py-10">
    <section class="card">
        <div class="flex flex-col items-center gap-6 border-b border-black/5 p-6 sm:flex-row">
            <img src="<?= avatar_url($user->avatar) ?>" alt="Avatar" class="h-24 w-24 rounded-full object-cover ring-4 ring-white/80">
            <div class="text-center sm:text-left">
                <p class="text-xl font-semibold"><?= e($user->fullName()) ?></p>
                <p class="text-sm text-neutral-500"><?= e($user->email) ?></p>
            </div>
            <a href="<?= url('profile-edit.php') ?>" class="btn btn-outline sm:ml-auto">Edit profile</a>
        </div>
        <dl class="divide-y divide-black/5">
            <?php foreach ($details as $label => $value): ?>
                <div class="grid gap-1 px-6 py-4 sm:grid-cols-3">
                    <dt class="text-sm text-neutral-500"><?= $label ?></dt>
                    <dd class="text-sm sm:col-span-2"><?= e($value !== null && $value !== '' ? $value : '-') ?></dd>
                </div>
            <?php endforeach; ?>
        </dl>
    </section>

    <div class="mt-6 text-right">
        <a href="<?= url('logout.php') ?>" class="btn btn-ghost text-red-600 hover:bg-red-50 hover:text-red-700">Log out</a>
    </div>
</div>

<?php require __DIR__ . '/includes/layout/footer.php'; ?>
