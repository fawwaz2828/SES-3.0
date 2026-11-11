<?php

declare(strict_types=1);

use Ses\Repository\ArticleRepository;

require __DIR__ . '/includes/bootstrap.php';

$articleRepository = new ArticleRepository(db());
$featured = $articleRepository->featured(4);
$popular = $articleRepository->popular(4);
$categories = $articleRepository->categories();

$search = query('search');
$selected = in_array(query('category'), $categories, true) ? query('category') : null;
$latest = $articleRepository->search($search, $selected);

/** blog.php URL that keeps the current filters. */
$filterUrl = static function (?string $category) use ($search): string {
    $params = array_filter(['search' => $search, 'category' => $category ?? '']);

    return url('blog.php' . ($params !== [] ? '?' . http_build_query($params) : '') . '#latest');
};

$title = 'Blog';
require __DIR__ . '/includes/layout/header.php';
?>

<section class="container-page pb-12 pt-16 lg:pt-24">
    <h1 class="max-w-3xl text-5xl font-normal leading-[1.05] tracking-tighter sm:text-6xl">
        Lighting the space. <span class="text-neutral-400">Smart tips &amp; trends.</span>
    </h1>
</section>

<?php if ($featured !== []): $main = array_shift($featured); ?>
    <section class="container-page grid gap-6 lg:grid-cols-2">
        <?php $article = $main; $layout = 'vertical'; $excerptLength = 260; require __DIR__ . '/includes/partials/article-card.php'; ?>
        <div class="grid gap-6">
            <?php foreach ($featured as $article) {
                $layout = 'horizontal';
                $excerptLength = 90;
                require __DIR__ . '/includes/partials/article-card.php';
            } ?>
        </div>
    </section>
<?php endif; ?>

<?php if ($popular !== []): ?>
    <section class="container-page mt-24">
        <h2 class="text-3xl font-semibold tracking-tight">Popular articles</h2>
        <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
            <?php foreach ($popular as $article) {
                $layout = 'vertical';
                $excerptLength = 0;
                $showViews = true;
                require __DIR__ . '/includes/partials/article-card.php';
            }
            $showViews = false; ?>
        </div>
    </section>
<?php endif; ?>

<section id="latest" class="container-page mt-24 scroll-mt-20">
    <div class="flex flex-col gap-6 md:flex-row md:items-end md:justify-between">
        <div>
            <h2 class="text-3xl font-semibold tracking-tight">Latest articles</h2>
            <p class="mt-1 text-neutral-500">Discover new smart tips</p>
        </div>
        <form action="<?= url('blog.php') ?>#latest" method="GET" class="relative w-full md:w-80">
            <?php if ($selected !== null): ?><input type="hidden" name="category" value="<?= e($selected) ?>"><?php endif; ?>
            <input type="search" name="search" value="<?= e($search) ?>" placeholder="Search articles" class="input rounded-full pr-11">
            <button class="absolute right-3 top-1/2 -translate-y-1/2 text-neutral-400 hover:text-neutral-900" aria-label="Search">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5-5m2-5a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z"/></svg>
            </button>
        </form>
    </div>

    <div class="mt-6 flex flex-wrap gap-2">
        <a href="<?= $filterUrl(null) ?>" class="pill <?= $selected === null ? 'pill-active' : 'pill-idle' ?>">All</a>
        <?php foreach ($categories as $category): ?>
            <a href="<?= $filterUrl($category) ?>" class="pill <?= $selected === $category ? 'pill-active' : 'pill-idle' ?>"><?= e($category) ?></a>
        <?php endforeach; ?>
    </div>

    <div class="mt-8 grid gap-6">
        <?php if ($latest !== []): ?>
            <?php foreach ($latest as $article) {
                $layout = 'horizontal';
                $excerptLength = 220;
                require __DIR__ . '/includes/partials/article-card.php';
            } ?>
        <?php else: ?>
            <?php $message = 'No articles found.'; require __DIR__ . '/includes/partials/empty-state.php'; ?>
        <?php endif; ?>
    </div>
</section>

<?php require __DIR__ . '/includes/layout/footer.php'; ?>
