<?php

declare(strict_types=1);

use Ses\Dto\Registration;
use Ses\Repository\UserRepository;
use Ses\Session\Flash;

require __DIR__ . '/includes/bootstrap.php';
require_guest();

$errors = [];

if (is_post()) {
    $registration = Registration::fromArray($_POST);
    $users = new UserRepository(db());

    $errors = $registration->errors();
    if ($errors === [] && $users->emailExists($registration->email)) {
        $errors[] = 'This email is already registered.';
    }

    if ($errors === []) {
        $users->create($registration);
        Flash::success('Registration successful! You can now log in.');
        redirect('login.php');
    }
}

$title = 'Register';
$heading = 'Join us today!';
$subheading = 'Welcome to the smart lighting revolution.';
$tagline = 'Transform your lighting with intelligent control and seamless integration.';
require __DIR__ . '/includes/layout/auth-start.php';
?>

<h2 class="text-3xl font-semibold tracking-tight">Create an account</h2>
<p class="mt-2 text-sm text-neutral-500">It only takes a minute.</p>

<?php if ($errors !== []): ?>
    <ul class="mt-6 space-y-1 rounded-xl bg-red-50 px-4 py-3 text-sm text-red-700">
        <?php foreach ($errors as $error): ?><li><?= e($error) ?></li><?php endforeach; ?>
    </ul>
<?php endif; ?>

<form method="POST" class="mt-8 space-y-5">
    <?= csrf_field() ?>
    <div class="grid gap-5 sm:grid-cols-2">
        <div>
            <label for="name" class="label">First name</label>
            <input id="name" name="name" value="<?= e(old('name')) ?>" required maxlength="50" autocomplete="given-name" class="input">
        </div>
        <div>
            <label for="last_name" class="label">Last name</label>
            <input id="last_name" name="last_name" value="<?= e(old('last_name')) ?>" required maxlength="50" autocomplete="family-name" class="input">
        </div>
    </div>
    <div>
        <label for="business_name" class="label">Business name</label>
        <input id="business_name" name="business_name" value="<?= e(old('business_name')) ?>" required maxlength="30" autocomplete="organization" class="input">
    </div>
    <div class="grid gap-5 sm:grid-cols-2">
        <div>
            <label for="email" class="label">Email</label>
            <input type="email" id="email" name="email" value="<?= e(old('email')) ?>" required maxlength="50" autocomplete="email" class="input">
        </div>
        <div>
            <label for="phone_number" class="label">Phone number</label>
            <input type="tel" id="phone_number" name="phone_number" value="<?= e(old('phone_number')) ?>" required maxlength="20" autocomplete="tel" class="input">
        </div>
    </div>
    <div class="grid gap-5 sm:grid-cols-2">
        <div>
            <label for="password" class="label">Password</label>
            <input type="password" id="password" name="password" required minlength="6" autocomplete="new-password" class="input">
        </div>
        <div>
            <label for="confirm_password" class="label">Confirm password</label>
            <input type="password" id="confirm_password" name="confirm_password" required minlength="6" autocomplete="new-password" class="input">
        </div>
    </div>
    <button type="submit" class="btn btn-primary w-full py-3">Create account</button>
</form>

<p class="mt-8 text-center text-sm text-neutral-500">
    Already have an account? <a href="<?= url('login.php') ?>" class="font-medium text-neutral-900 underline-offset-4 hover:underline">Log in here</a>
</p>

<?php require __DIR__ . '/includes/layout/auth-end.php'; ?>
