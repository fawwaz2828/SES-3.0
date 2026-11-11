<?php

declare(strict_types=1);

use Ses\Dto\ArticleInput;
use Ses\Http\UploadedImage;
use Ses\Http\UploadException;
use Ses\Repository\ArticleRepository;
use Ses\Session\Flash;
use Ses\Support\Input;

require dirname(__DIR__) . '/includes/bootstrap.php';
$adminName = require_admin();

$articles = new ArticleRepository(db());

if (is_post()) {
    $id = Input::int($_POST, 'id');

    switch (Input::string($_POST, 'action')) {
        case 'delete':
            if ($id !== null) {
                $articles->delete($id);
                Flash::success('Post deleted.');
            }
            break;

        case 'toggle':
            if ($id !== null) {
                $articles->toggleVisibility($id);
                Flash::success('Visibility updated.');
            }
            break;

        case 'add':
            $input = ArticleInput::fromArray($_POST);
            $errors = $input->errors();
            try {
                $image = UploadedImage::fromRequest('image', 10 * 1024 * 1024);
                if ($image === null) {
                    $errors[] = 'Please upload a cover image.';
                }
            } catch (UploadException $e) {
                $image = null;
                $errors[] = $e->getMessage();
            }

            if ($errors === [] && $image !== null) {
                $articles->create($input, $image, $adminName);
                Flash::success('Post published.');
            } else {
                Flash::error(implode(' ', $errors));
            }
            break;
    }
    redirect('admin/blogs.php');
}

$posts = $articles->all();
$categories = $articles->allCategories();

$title = 'Blog';
require dirname(__DIR__) . '/includes/layout/admin-start.php';
?>

<h1 class="text-2xl font-semibold tracking-tight">Blog</h1>
<p class="mt-1 text-sm text-neutral-500">Write and manage articles on the blog page.</p>

<div class="mt-8 grid gap-6 xl:grid-cols-[1fr_24rem]">
    <div class="card overflow-x-auto">
        <table class="min-w-full text-left text-sm">
            <thead class="border-b border-black/5 text-xs uppercase tracking-wider text-neutral-500">
                <tr>
                    <th class="px-6 py-3 font-medium">Title</th>
                    <th class="px-6 py-3 font-medium">Views</th>
                    <th class="px-6 py-3 font-medium">Status</th>
                    <th class="px-6 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-black/5">
                <?php foreach ($posts as $post): ?>
                    <tr class="hover:bg-white/50">
                        <td class="px-6 py-3">
                            <p class="font-medium"><?= e($post->title) ?></p>
                            <p class="text-xs text-neutral-500"><?= e($post->category) ?> · <?= format_date($post->createdAt) ?></p>
                        </td>
                        <td class="px-6 py-3 text-neutral-600"><?= number_format($post->viewCount) ?></td>
                        <td class="px-6 py-3">
                            <form method="POST">
                                <?= csrf_field() ?>
                                <input type="hidden" name="action" value="toggle">
                                <input type="hidden" name="id" value="<?= $post->id ?>">
                                <button class="badge <?= $post->visible ? 'bg-emerald-100 text-emerald-700' : 'bg-neutral-200 text-neutral-600' ?>" title="Click to toggle">
                                    <?= $post->visible ? 'Published' : 'Hidden' ?>
                                </button>
                            </form>
                        </td>
                        <td class="px-6 py-3 text-right">
                            <form method="POST" onsubmit="return confirm('Delete this post?')">
                                <?= csrf_field() ?>
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?= $post->id ?>">
                                <button class="btn btn-ghost btn-sm text-red-600 hover:bg-red-50 hover:text-red-700">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if ($posts === []): ?>
                    <tr><td colspan="4" class="px-6 py-10 text-center text-neutral-500">No posts yet.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <section class="card self-start p-6">
        <h2 class="font-semibold">New post</h2>
        <form method="POST" enctype="multipart/form-data" class="mt-5 space-y-4">
            <?= csrf_field() ?>
            <input type="hidden" name="action" value="add">
            <div>
                <label for="title" class="label">Title</label>
                <input id="title" name="title" required maxlength="255" class="input">
            </div>
            <div>
                <label for="category" class="label">Category</label>
                <input id="category" name="category" list="categories" required maxlength="50" class="input">
                <datalist id="categories">
                    <?php foreach ($categories as $category): ?><option value="<?= e($category) ?>"><?php endforeach; ?>
                </datalist>
            </div>
            <div>
                <label for="image" class="label">Cover image</label>
                <input type="file" id="image" name="image" accept="image/*" required
                       class="block w-full text-sm text-neutral-500 file:mr-3 file:rounded-full file:border-0 file:bg-black/5 file:px-4 file:py-2 file:text-sm file:font-medium file:text-neutral-900 hover:file:bg-neutral-200">
            </div>
            <div>
                <label for="content" class="label">Content</label>
                <textarea id="content" name="content" rows="8" required class="input"></textarea>
            </div>
            <button class="btn btn-primary w-full">Publish</button>
        </form>
    </section>
</div>

<?php require dirname(__DIR__) . '/includes/layout/admin-end.php'; ?>
