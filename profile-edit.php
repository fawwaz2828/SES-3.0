<?php

declare(strict_types=1);

use Ses\App;
use Ses\Dto\ProfileUpdate;
use Ses\Http\UploadedImage;
use Ses\Http\UploadException;
use Ses\Repository\UserRepository;
use Ses\Service\AvatarStorage;
use Ses\Session\Auth;
use Ses\Session\Flash;

require __DIR__ . '/includes/bootstrap.php';
$userId = require_login();

$users = new UserRepository(db());
$user = $users->find($userId) ?? redirect('logout.php');

// Values shown in the form: what was just submitted, or what is stored.
$form = ProfileUpdate::fromArray(is_post() ? $_POST : [
    'name' => $user->name,
    'last_name' => $user->lastName,
    'phone_number' => $user->phoneNumber ?? '',
    'business_name' => $user->businessName ?? '',
]);
$errors = [];

if (is_post()) {
    $errors = $form->errors();
    try {
        $image = UploadedImage::fromRequest('avatar');
    } catch (UploadException $e) {
        $image = null;
        $errors[] = $e->getMessage();
    }

    if ($errors === []) {
        $avatar = $user->avatar;
        if ($image !== null) {
            $storage = new AvatarStorage(App::rootDir());
            $avatar = $storage->store($userId, $image);
            $storage->delete($user->avatar);
        }

        $users->updateProfile($userId, $form, $avatar);
        Auth::rename($form->name);
        Flash::success('Profile updated.');
        redirect('profile.php');
    }
}

$title = 'Edit Profile';
require __DIR__ . '/includes/layout/header.php';
?>

<div class="container-page max-w-2xl py-10">
    <a href="<?= url('profile.php') ?>" class="text-sm text-neutral-500 hover:text-neutral-900">← Back to profile</a>
    <h1 class="mt-6 text-3xl font-semibold tracking-tight">Edit profile</h1>

    <form method="POST" enctype="multipart/form-data" class="card mt-8 space-y-6 p-6 sm:p-8">
        <?= csrf_field() ?>

        <?php if ($errors !== []): ?>
            <ul class="space-y-1 rounded-xl bg-red-50 px-4 py-3 text-sm text-red-700">
                <?php foreach ($errors as $error): ?><li><?= e($error) ?></li><?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <div class="flex items-center gap-5">
            <img src="<?= avatar_url($user->avatar) ?>" alt="Avatar" class="h-20 w-20 rounded-full object-cover ring-4 ring-white/80" data-avatar-preview>
            <div>
                <label for="avatar" class="btn btn-outline btn-sm cursor-pointer">Change photo</label>
                <input type="file" id="avatar" name="avatar" accept="image/*" class="sr-only">
                <p class="mt-2 text-xs text-neutral-500">JPG, PNG, GIF or WEBP. Max 5 MB.</p>
            </div>
        </div>

        <div class="grid gap-5 sm:grid-cols-2">
            <div>
                <label for="name" class="label">First name</label>
                <input id="name" name="name" value="<?= e($form->name) ?>" required maxlength="50" class="input">
            </div>
            <div>
                <label for="last_name" class="label">Last name</label>
                <input id="last_name" name="last_name" value="<?= e($form->lastName) ?>" required maxlength="50" class="input">
            </div>
        </div>
        <div>
            <label for="email" class="label">Email</label>
            <input type="email" id="email" value="<?= e($user->email) ?>" readonly class="input text-neutral-500">
            <p class="mt-1.5 text-xs text-neutral-500">Email cannot be changed.</p>
        </div>
        <div class="grid gap-5 sm:grid-cols-2">
            <div>
                <label for="phone_number" class="label">Phone number</label>
                <input type="tel" id="phone_number" name="phone_number" value="<?= e($form->phoneNumber) ?>" maxlength="20" class="input">
            </div>
            <div>
                <label for="business_name" class="label">Business name</label>
                <input id="business_name" name="business_name" value="<?= e($form->businessName) ?>" maxlength="30" class="input">
            </div>
        </div>

        <div class="flex justify-end gap-3 border-t border-black/5 pt-6">
            <a href="<?= url('profile.php') ?>" class="btn btn-outline">Cancel</a>
            <button type="submit" class="btn btn-primary">Save changes</button>
        </div>
    </form>
</div>

<script>
    document.getElementById('avatar').addEventListener('change', (event) => {
        const [file] = event.target.files;
        if (file) document.querySelector('[data-avatar-preview]').src = URL.createObjectURL(file);
    });
</script>

<?php require __DIR__ . '/includes/layout/footer.php'; ?>
