<?php

declare(strict_types=1);

use Ses\Session\Flash;

/** Shows messages set with Flash::success() / Flash::error(). */
$flashes = Flash::pull();
if ($flashes === []) {
    return;
}
?>
<div class="pointer-events-none fixed inset-x-0 top-20 z-[60] sm:top-24 flex flex-col items-center gap-2 px-4" data-flash>
    <?php foreach ($flashes as $flash): ?>
        <div class="glass pointer-events-auto flex w-full max-w-md items-start gap-3 rounded-2xl px-4 py-3 text-sm <?= $flash->type->classes() ?>">
            <span class="flex-1"><?= e($flash->message) ?></span>
            <button type="button" class="opacity-60 hover:opacity-100" onclick="this.parentElement.remove()" aria-label="Close">&times;</button>
        </div>
    <?php endforeach; ?>
</div>
<script>
    setTimeout(() => document.querySelector('[data-flash]')?.remove(), 5000);
</script>
