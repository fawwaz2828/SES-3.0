<?php

declare(strict_types=1);

use Ses\Repository\ProductRepository;

require __DIR__ . '/includes/bootstrap.php';

$productRepository = new ProductRepository(db());
$categories = $productRepository->categories();
$selected = in_array(query('category'), $categories, true) ? query('category') : null;
$products = $productRepository->latest($selected);

$title = 'Explore';
require __DIR__ . '/includes/layout/header.php';
?>

<section class="container-page pb-12 pt-16 lg:pt-24">
    <p class="text-lg text-neutral-500 sm:text-2xl">Discover our smart lighting solutions</p>
    <h1 class="mt-3 max-w-4xl text-5xl font-normal leading-[1.05] tracking-tighter sm:text-6xl lg:text-7xl">
        Transform your space with intelligent shine.
    </h1>
</section>

<section class="container-page grid gap-10 lg:grid-cols-[14rem_1fr]">
    <aside class="lg:sticky lg:top-28 lg:self-start">
        <h2 class="text-sm font-semibold uppercase tracking-wider text-neutral-500">Categories</h2>
        <nav class="mt-4 flex flex-wrap gap-2 lg:flex-col lg:gap-1">
            <?php foreach ([null, ...$categories] as $category): $active = $category === $selected; ?>
                <a href="<?= url('explore.php' . ($category !== null ? '?category=' . urlencode($category) : '')) ?>"
                   class="rounded-full px-4 py-2 text-sm transition lg:rounded-xl <?= $active ? 'bg-neutral-900 text-white' : 'text-neutral-600 hover:bg-white/60 hover:text-neutral-900' ?>">
                    <?= $category === null ? 'All products' : e($category) ?>
                </a>
            <?php endforeach; ?>
        </nav>
    </aside>

    <div>
        <p class="mb-6 text-sm text-neutral-500"><?= count($products) ?> product<?= count($products) === 1 ? '' : 's' ?></p>
        <?php if ($products !== []): ?>
            <div class="grid grid-cols-2 gap-x-4 gap-y-10 md:grid-cols-3 xl:grid-cols-4">
                <?php foreach ($products as $product) {
                    require __DIR__ . '/includes/partials/product-card.php';
                } ?>
            </div>
        <?php else: ?>
            <?php $message = 'No products available.'; require __DIR__ . '/includes/partials/empty-state.php'; ?>
        <?php endif; ?>
    </div>
</section>

<?php require __DIR__ . '/includes/layout/footer.php'; ?>
