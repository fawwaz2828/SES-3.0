<?php

declare(strict_types=1);

use Ses\Repository\ArticleRepository;
use Ses\Session\Flash;

require __DIR__ . '/includes/bootstrap.php';

$articleRepository = new ArticleRepository(db());
$post = $articleRepository->findVisible((int) query('id'));
if ($post === null) {
    Flash::error('Article not found.');
    redirect('blog.php');
}

$ip = $_SERVER['REMOTE_ADDR'] ?? '';
if ($articleRepository->recordView($post->id, is_string($ip) ? $ip : '')) {
    $post = $post->withViewCount($post->viewCount + 1);
}
$related = $articleRepository->related($post, 2);

$title = $post->title;
require __DIR__ . '/includes/layout/header.php';
?>

<article class="container-page max-w-3xl pt-10">
    <a href="<?= url('blog.php') ?>" class="text-sm text-neutral-500 hover:text-neutral-900">← Back to articles</a>

    <header class="mt-8">
        <div class="flex flex-wrap items-center gap-3 text-sm text-neutral-500">
            <span class="badge bg-neutral-900 text-white"><?= e($post->category) ?></span>
            <span><?= format_date($post->createdAt) ?></span>
            <span>·</span>
            <span><?= number_format($post->viewCount) ?> views</span>
        </div>
        <h1 class="mt-5 text-4xl font-semibold leading-tight tracking-tight sm:text-5xl"><?= e($post->title) ?></h1>
        <?php if ($post->author !== ''): ?>
            <p class="mt-4 text-sm text-neutral-500">By <?= e($post->author) ?></p>
        <?php endif; ?>
    </header>

    <?php if (($src = blob_src($post->image)) !== null): ?>
        <img src="<?= $src ?>" alt="<?= e($post->title) ?>" class="mt-10 aspect-[16/9] w-full rounded-3xl object-cover">
    <?php endif; ?>

    <div class="mt-10 text-lg leading-8 text-neutral-700">
        <?= nl2br(e($post->content)) ?>
    </div>
</article>

<?php if ($related !== []): ?>
    <section class="container-page mt-24 max-w-3xl">
        <h2 class="text-2xl font-semibold tracking-tight">Also check</h2>
        <div class="mt-6 grid gap-6 sm:grid-cols-2">
            <?php foreach ($related as $article) {
                $excerptLength = 0;
                require __DIR__ . '/includes/partials/article-card.php';
            } ?>
        </div>
    </section>
<?php endif; ?>

<?php require __DIR__ . '/includes/layout/footer.php'; ?>
