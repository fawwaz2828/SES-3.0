<?php

declare(strict_types=1);

use Ses\Repository\ProductRepository;
use Ses\Session\Flash;

require __DIR__ . '/includes/bootstrap.php';

$productRepository = new ProductRepository(db());
$item = $productRepository->find((int) query('id'));
if ($item === null) {
    Flash::error('Product not found.');
    redirect('explore.php');
}
$related = $productRepository->related($item, 4);

$title = $item->name;
require __DIR__ . '/includes/layout/header.php';
?>

<div class="container-page pt-8">
    <a href="<?= url('explore.php') ?>" class="text-sm text-neutral-500 hover:text-neutral-900">← Back to products</a>

    <div class="mt-6 grid gap-10 lg:grid-cols-2 lg:gap-16">
        <div class="aspect-square overflow-hidden rounded-3xl bg-black/5">
            <?php if (($src = blob_src($item->image)) !== null): ?>
                <img src="<?= $src ?>" alt="<?= e($item->name) ?>" class="h-full w-full object-cover">
            <?php endif; ?>
        </div>

        <div class="flex flex-col lg:py-6">
            <span class="badge self-start bg-black/5 text-neutral-700"><?= e($item->category) ?></span>
            <h1 class="mt-4 text-4xl font-semibold tracking-tight lg:text-5xl"><?= e($item->name) ?></h1>
            <p class="mt-4 text-2xl"><?= format_price($item->price) ?></p>

            <?php if ($item->description !== null && $item->description !== ''): ?>
                <div class="mt-8 border-t border-black/5 pt-8 leading-relaxed text-neutral-600">
                    <?= nl2br(e($item->description)) ?>
                </div>
            <?php endif; ?>

            <div class="mt-10 flex flex-wrap gap-3 lg:mt-auto">
                <a href="<?= url('consultation-form.php') ?>" class="btn btn-primary px-6 py-3">Ask us about this product</a>
                <a href="<?= url('explore.php?category=' . urlencode($item->category)) ?>" class="btn btn-outline px-6 py-3">More <?= e($item->category) ?></a>
            </div>
        </div>
    </div>
</div>

<?php if ($related !== []): ?>
    <section class="container-page mt-24">
        <h2 class="text-2xl font-semibold tracking-tight">You might also like</h2>
        <div class="mt-6 grid grid-cols-2 gap-x-4 gap-y-10 md:grid-cols-4">
            <?php foreach ($related as $product) {
                require __DIR__ . '/includes/partials/product-card.php';
            } ?>
        </div>
    </section>
<?php endif; ?>

<?php require __DIR__ . '/includes/layout/footer.php'; ?>
