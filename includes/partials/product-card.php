<?php

declare(strict_types=1);

/** @var Ses\Model\Product $product */
?>
<a href="<?= url('product.php?id=' . $product->id) ?>" class="group block">
    <div class="aspect-square overflow-hidden rounded-2xl bg-black/5">
        <?php if (($src = blob_src($product->image)) !== null): ?>
            <img src="<?= $src ?>" alt="<?= e($product->name) ?>" loading="lazy"
                 class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
        <?php endif; ?>
    </div>
    <div class="mt-3 flex items-start justify-between gap-3">
        <div>
            <p class="font-medium leading-snug group-hover:underline"><?= e($product->name) ?></p>
            <p class="mt-0.5 text-xs text-neutral-500"><?= e($product->category) ?></p>
        </div>
        <p class="shrink-0 text-sm font-medium"><?= format_price($product->price) ?></p>
    </div>
</a>
