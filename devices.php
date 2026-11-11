<?php

declare(strict_types=1);

use Ses\Repository\DeviceRepository;
use Ses\Session\Flash;
use Ses\Support\Input;

require __DIR__ . '/includes/bootstrap.php';
$userId = require_login();

$devices = new DeviceRepository(db());

if (is_post()) {
    $deleteId = Input::int($_POST, 'delete_id');
    $name = Input::string($_POST, 'device_name', 255);

    if ($deleteId !== null) {
        $devices->delete($deleteId, $userId);
        Flash::success('Device removed.');
    } elseif ($name === '') {
        Flash::error('Device name cannot be empty.');
    } else {
        $devices->add($userId, $name);
        Flash::success(sprintf('Device "%s" connected.', $name));
    }
    redirect('devices.php');
}

$list = $devices->forUser($userId);

$title = 'My Devices';
require __DIR__ . '/includes/layout/header.php';

$heading = 'My Devices';
$activeTab = 'devices';
require __DIR__ . '/includes/layout/user-tabs.php';
?>

<div class="container-page grid gap-6 py-10 lg:grid-cols-[1fr_22rem]">
    <section class="card">
        <header class="flex items-center justify-between border-b border-black/5 px-6 py-4">
            <h2 class="font-semibold">Connected devices</h2>
            <span class="badge bg-black/5 text-neutral-600"><?= count($list) ?></span>
        </header>

        <?php if ($list !== []): ?>
            <ul class="divide-y divide-black/5">
                <?php foreach ($list as $device): ?>
                    <li class="flex items-center justify-between gap-4 px-6 py-4">
                        <div class="flex min-w-0 items-center gap-4">
                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-black/5">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9 18h6m-5 3h4M12 3a6 6 0 0 0-3.6 10.8c.6.5 1.1 1.2 1.1 2V16h5v-.2c0-.8.4-1.5 1.1-2A6 6 0 0 0 12 3Z"/></svg>
                            </span>
                            <div class="min-w-0">
                                <p class="truncate font-medium"><?= e($device->name) ?></p>
                                <p class="text-xs text-neutral-500">Added <?= format_date($device->createdAt) ?></p>
                            </div>
                        </div>
                        <form method="POST" onsubmit="return confirm('Remove this device?')">
                            <?= csrf_field() ?>
                            <input type="hidden" name="delete_id" value="<?= $device->id ?>">
                            <button class="btn btn-ghost btn-sm text-red-600 hover:bg-red-50 hover:text-red-700">Remove</button>
                        </form>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p class="px-6 py-10 text-center text-sm text-neutral-500">You don't have any device yet.</p>
        <?php endif; ?>
    </section>

    <section class="card self-start p-6">
        <h2 class="font-semibold">Connect a device</h2>
        <p class="mt-1 text-sm text-neutral-500">Give your new smart lamp a name you'll recognize.</p>
        <form method="POST" class="mt-5 space-y-4">
            <?= csrf_field() ?>
            <div>
                <label for="device_name" class="label">Device name</label>
                <input id="device_name" name="device_name" required maxlength="255" placeholder="e.g. Meeting room lamp" class="input">
            </div>
            <button class="btn btn-primary w-full">Connect device</button>
        </form>
    </section>
</div>

<?php require __DIR__ . '/includes/layout/footer.php'; ?>
