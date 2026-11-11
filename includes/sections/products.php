<?php

declare(strict_types=1);

use Ses\Repository\ProductRepository;

$productRepository = new ProductRepository(db());
$categories = $productRepository->categories();
$selected = in_array(query('category'), $categories, true) ? query('category') : null;
$products = $productRepository->latest($selected, 5);
?>
<!-- Product categories -->
<section id="products" class="container-page scroll-mt-20 py-24">
    <h2 class="text-center text-4xl font-semibold tracking-tight md:text-5xl">
        Various Categories, <span class="text-neutral-400">Tons of Possibilities.</span>
    </h2>

    <div class="mt-10 flex flex-wrap justify-center gap-2">
        <a href="<?= url('index.php#products') ?>" class="pill <?= $selected === null ? 'pill-active' : 'pill-idle' ?>">All</a>
        <?php foreach ($categories as $category): ?>
            <a href="<?= url('index.php?category=' . urlencode($category) . '#products') ?>"
               class="pill <?= $selected === $category ? 'pill-active' : 'pill-idle' ?>"><?= e($category) ?></a>
        <?php endforeach; ?>
    </div>

    <?php if ($products !== []): ?>
        <div class="mt-12 grid grid-cols-2 gap-x-4 gap-y-8 md:grid-cols-3 lg:grid-cols-5">
            <?php foreach ($products as $product) {
                require dirname(__DIR__) . '/partials/product-card.php';
            } ?>
        </div>
    <?php else: ?>
        <div class="mt-12"><?php $message = 'No products in this category yet.'; require dirname(__DIR__) . '/partials/empty-state.php'; ?></div>
    <?php endif; ?>

    <div class="mt-12 text-center">
        <a href="<?= url('explore.php') ?>" class="btn btn-outline">See all products</a>
    </div>
</section>
