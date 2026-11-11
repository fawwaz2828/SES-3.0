<?php

declare(strict_types=1);

/**
 * @var Ses\Model\Article $article
 * @var 'vertical'|'horizontal'|null $layout
 * @var int|null $excerptLength 0 hides the excerpt
 * @var bool|null $showViews
 */
$horizontal = ($layout ?? 'vertical') === 'horizontal';
$excerptChars = $excerptLength ?? 120;
?>
<a href="<?= url('article.php?id=' . $article->id) ?>"
   class="card group flex overflow-hidden transition duration-300 hover:-translate-y-1 hover:bg-white/60 <?= $horizontal ? 'flex-col sm:flex-row' : 'flex-col' ?>">
    <div class="shrink-0 overflow-hidden bg-black/5 <?= $horizontal ? 'aspect-[16/10] sm:aspect-auto sm:w-2/5' : 'aspect-[16/10]' ?>">
        <?php if (($src = blob_src($article->image)) !== null): ?>
            <img src="<?= $src ?>" alt="<?= e($article->title) ?>" loading="lazy"
                 class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
        <?php endif; ?>
    </div>
    <div class="flex flex-1 flex-col gap-3 p-5">
        <div class="flex items-center gap-2 text-xs text-neutral-500">
            <span class="badge bg-black/5 text-neutral-700"><?= e($article->category) ?></span>
            <span><?= format_date($article->createdAt) ?></span>
            <?php if ($showViews ?? false): ?>
                <span>· <?= number_format($article->viewCount) ?> views</span>
            <?php endif; ?>
        </div>
        <h3 class="text-lg font-semibold leading-snug tracking-tight group-hover:underline"><?= e($article->title) ?></h3>
        <?php if ($excerptChars > 0): ?>
            <p class="text-sm leading-relaxed text-neutral-600"><?= e(excerpt($article->content, $excerptChars)) ?></p>
        <?php endif; ?>
    </div>
</a>
