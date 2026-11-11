<?php

declare(strict_types=1);

use Ses\Repository\UserRepository;
use Ses\Session\Auth;
use Ses\Support\Input;

require __DIR__ . '/includes/bootstrap.php';
require_guest();

$error = '';

if (is_post()) {
    $user = (new UserRepository(db()))->findByEmail(Input::string($_POST, 'email'));
    $password = $_POST['password'] ?? '';

    if ($user !== null && is_string($password) && $user->verifyPassword($password)) {
        Auth::login($user);
        redirect('dashboard.php');
    }
    $error = 'Incorrect email or password.';
}

$title = 'Log in';
$heading = 'Welcome back!';
$subheading = 'Your next step is just a login away.';
$tagline = 'Manage your consultations and smart devices in one place.';
require __DIR__ . '/includes/layout/auth-start.php';
?>

<h2 class="text-3xl font-semibold tracking-tight">Log in</h2>
<p class="mt-2 text-sm text-neutral-500">Enter your email and password to continue.</p>

<?php if ($error !== ''): ?>
    <p class="mt-6 rounded-xl bg-red-50 px-4 py-3 text-sm text-red-700"><?= e($error) ?></p>
<?php endif; ?>

<form method="POST" class="mt-8 space-y-5">
    <?= csrf_field() ?>
    <div>
        <label for="email" class="label">Email</label>
        <input type="email" id="email" name="email" value="<?= e(old('email')) ?>" required autofocus autocomplete="email" class="input">
    </div>
    <div>
        <label for="password" class="label">Password</label>
        <input type="password" id="password" name="password" required autocomplete="current-password" class="input">
    </div>
    <button type="submit" class="btn btn-primary w-full py-3">Log in</button>
</form>

<p class="mt-8 text-center text-sm text-neutral-500">
    Don't have an account? <a href="<?= url('register.php') ?>" class="font-medium text-neutral-900 underline-offset-4 hover:underline">Register here</a>
</p>

<?php require __DIR__ . '/includes/layout/auth-end.php'; ?>
