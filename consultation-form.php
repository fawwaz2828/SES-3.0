<?php

declare(strict_types=1);

use Ses\Dto\ConsultationRequest;
use Ses\Repository\ConsultationRepository;
use Ses\Session\Flash;
use Ses\Support\Input;

require __DIR__ . '/includes/bootstrap.php';
$userId = require_login();

$today = new DateTimeImmutable('today');
$errors = [];

if (is_post()) {
    $request = ConsultationRequest::fromArray($_POST);
    $errors = $request->errors($today);

    if ($errors === []) {
        (new ConsultationRepository(db()))->create($request, $userId);
        Flash::success('Thanks! Your consultation request has been submitted.');
        redirect('dashboard.php');
    }
}

$checked = static fn(string $field): string => Input::bool($_POST, $field) ? 'checked' : '';

$title = 'Book a Consultation';
require __DIR__ . '/includes/layout/header.php';
?>

<div class="container-page grid gap-12 py-12 lg:grid-cols-[1fr_2fr] lg:gap-20 lg:py-16">
    <div class="lg:sticky lg:top-28 lg:self-start">
        <a href="<?= url('dashboard.php') ?>" class="text-sm text-neutral-500 hover:text-neutral-900">← Back to dashboard</a>
        <h1 class="mt-6 text-4xl font-semibold leading-tight tracking-tight lg:text-5xl">
            Tell us your story, <span class="text-neutral-400">and get advice soon.</span>
        </h1>
        <p class="mt-4 text-neutral-500">Our team will review your request and contact you to schedule a site survey.</p>
    </div>

    <form method="POST" class="space-y-8">
        <?= csrf_field() ?>

        <?php if ($errors !== []): ?>
            <ul class="space-y-1 rounded-xl bg-red-50 px-4 py-3 text-sm text-red-700">
                <?php foreach ($errors as $error): ?><li><?= e($error) ?></li><?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <fieldset class="card space-y-5 p-6 sm:p-8">
            <legend class="sr-only">Company detail</legend>
            <div>
                <h2 class="text-lg font-semibold">Company detail</h2>
                <p class="text-sm text-neutral-500">Tell us about your company.</p>
            </div>
            <div class="grid gap-5 sm:grid-cols-2">
                <div>
                    <label for="company_name" class="label">Company name</label>
                    <input id="company_name" name="company_name" value="<?= e(old('company_name')) ?>" placeholder="e.g. SES" required maxlength="255" class="input">
                </div>
                <div>
                    <label for="company_field" class="label">Company field</label>
                    <input id="company_field" name="company_field" value="<?= e(old('company_field')) ?>" placeholder="e.g. Retail, Architecture" required maxlength="255" class="input">
                </div>
                <div>
                    <label for="company_size" class="label">Company size</label>
                    <input type="number" min="1" id="company_size" name="company_size" value="<?= e(old('company_size')) ?>" placeholder="Number of employees" required class="input">
                </div>
                <div>
                    <label for="company_address" class="label">Company address</label>
                    <input id="company_address" name="company_address" value="<?= e(old('company_address')) ?>" placeholder="City and district" required class="input">
                </div>
            </div>
        </fieldset>

        <fieldset class="card space-y-5 p-6 sm:p-8">
            <legend class="sr-only">Consultation detail</legend>
            <div>
                <h2 class="text-lg font-semibold">Consultation detail</h2>
                <p class="text-sm text-neutral-500">The more we know, the better our advice.</p>
            </div>
            <?php foreach (['current_lighting' => 'Current lighting setup', 'problem_detail' => 'Problem detail', 'goals' => 'Goals'] as $field => $label): ?>
                <div>
                    <label for="<?= $field ?>" class="label"><?= $label ?></label>
                    <textarea id="<?= $field ?>" name="<?= $field ?>" rows="3" class="input"><?= e(old($field)) ?></textarea>
                </div>
            <?php endforeach; ?>
            <div class="grid gap-5 sm:grid-cols-3">
                <div>
                    <label for="min_budget" class="label">Min budget (Rp)</label>
                    <input type="number" min="0" id="min_budget" name="min_budget" value="<?= e(old('min_budget')) ?>" class="input">
                </div>
                <div>
                    <label for="max_budget" class="label">Max budget (Rp)</label>
                    <input type="number" min="0" id="max_budget" name="max_budget" value="<?= e(old('max_budget')) ?>" class="input">
                </div>
                <div>
                    <label for="preferred_date" class="label">Preferred date</label>
                    <input type="date" min="<?= $today->format('Y-m-d') ?>" id="preferred_date" name="preferred_date" value="<?= e(old('preferred_date')) ?>" required class="input">
                </div>
            </div>
        </fieldset>

        <div class="space-y-3 text-sm text-neutral-600">
            <label class="flex gap-3">
                <input type="checkbox" name="privacy_policy" required class="checkbox" <?= $checked('privacy_policy') ?>>
                I agree to the processing of my personal data in accordance with the privacy policy.
            </label>
            <label class="flex gap-3">
                <input type="checkbox" name="updates_promotions" class="checkbox" <?= $checked('updates_promotions') ?>>
                Send me updates, promotions, and news about SES products and services (optional).
            </label>
        </div>

        <div class="flex justify-end gap-3">
            <a href="<?= url('dashboard.php') ?>" class="btn btn-outline">Cancel</a>
            <button type="submit" class="btn btn-primary">Submit request</button>
        </div>
    </form>
</div>

<?php require __DIR__ . '/includes/layout/footer.php'; ?>
