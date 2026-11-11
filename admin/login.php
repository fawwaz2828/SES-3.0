<?php

declare(strict_types=1);

use Ses\Repository\AdminRepository;
use Ses\Session\Auth;
use Ses\Support\Input;

require dirname(__DIR__) . '/includes/bootstrap.php';

if (Auth::adminName() !== null) {
    redirect('admin/dashboard.php');
}

$error = '';

if (is_post()) {
    $admin = (new AdminRepository(db()))->findByLogin(Input::string($_POST, 'username'));
    $password = $_POST['password'] ?? '';

    if ($admin !== null && is_string($password) && $admin->verifyPassword($password)) {
        Auth::loginAdmin($admin);
        redirect('admin/dashboard.php');
    }
    $error = 'Incorrect username or password.';
}

$title = 'Admin login';
$bodyClass = 'items-center justify-center px-4';
require dirname(__DIR__) . '/includes/layout/head.php';
?>

<div class="glass w-full max-w-sm rounded-[2.5rem] p-8">
    <img src="<?= url('assets/images/logo-black.svg') ?>" alt="SES" class="h-7 w-auto">
    <h1 class="mt-8 text-2xl font-semibold tracking-tight">Admin login</h1>
    <p class="mt-1 text-sm text-neutral-500">Sign in to manage the SES website.</p>

    <?php if ($error !== ''): ?>
        <p class="mt-6 rounded-xl bg-red-50 px-4 py-3 text-sm text-red-700"><?= e($error) ?></p>
    <?php endif; ?>

    <form method="POST" class="mt-6 space-y-5">
        <?= csrf_field() ?>
        <div>
            <label for="username" class="label">Username or email</label>
            <input id="username" name="username" value="<?= e(old('username')) ?>" required autofocus class="input">
        </div>
        <div>
            <label for="password" class="label">Password</label>
            <input type="password" id="password" name="password" required class="input">
        </div>
        <button class="btn btn-primary w-full py-3">Log in</button>
    </form>

    <a href="<?= url('index.php') ?>" class="mt-6 block text-center text-sm text-neutral-500 hover:text-neutral-900">← Back to website</a>
</div>

</body>
</html>
