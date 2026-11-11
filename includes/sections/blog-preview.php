<?php

declare(strict_types=1);

use Ses\Repository\ArticleRepository;

$articles = (new ArticleRepository(db()))->featured(3);
?>
<!-- Latest articles + final call to action -->
<section class="container-page py-24">
    <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
        <h2 class="text-4xl font-normal tracking-tight">Still Not Sure?</h2>
        <p class="text-lg text-neutral-500 md:text-right">Discover tips, trends, and inspiration<br class="hidden md:block"> for smarter living.</p>
    </div>

    <?php if ($articles !== []): ?>
        <div class="mt-10 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            <?php foreach ($articles as $article) {
                require dirname(__DIR__) . '/partials/article-card.php';
            } ?>
        </div>
    <?php endif; ?>

    <div class="mt-10 text-center">
        <a href="<?= url('blog.php') ?>" class="text-sm font-medium underline-offset-4 hover:underline">Browse all posts →</a>
    </div>

    <div class="mx-auto mt-32 max-w-2xl text-center">
        <p class="text-4xl font-extralight leading-tight tracking-tighter md:text-5xl">
            Be part of future hope with light that makes the planet smile.
        </p>
        <p class="mt-4 text-xl font-extralight tracking-tight text-neutral-500">
            When artistry meets efficiency: shine smarter, work better.
        </p>
        <a href="<?= url('consultation-form.php') ?>" class="btn btn-primary mt-10 px-8 py-3 text-base">Book a Consultation</a>
    </div>
</section>
