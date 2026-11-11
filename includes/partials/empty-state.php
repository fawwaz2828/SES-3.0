<?php

declare(strict_types=1);

/**
 * @var string $message
 * @var string|null $actionLabel
 * @var string|null $actionHref
 */
?>
<div class="rounded-2xl border border-dashed border-neutral-300 px-6 py-12 text-center">
    <p class="text-sm text-neutral-500"><?= e($message) ?></p>
    <?php if (isset($actionHref, $actionLabel)): ?>
        <a href="<?= url($actionHref) ?>" class="btn btn-outline btn-sm mt-4"><?= e($actionLabel) ?></a>
    <?php endif; ?>
</div>
<?php unset($actionHref, $actionLabel); ?>
