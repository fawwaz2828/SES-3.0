<?php

declare(strict_types=1);

use Ses\Dto\ProductInput;
use Ses\Http\UploadedImage;
use Ses\Http\UploadException;
use Ses\Repository\ProductRepository;
use Ses\Session\Flash;
use Ses\Support\Input;

require dirname(__DIR__) . '/includes/bootstrap.php';
require_admin();

$products = new ProductRepository(db());

if (is_post()) {
    $deleteId = Input::int($_POST, 'delete_id');
    if ($deleteId !== null) {
        $products->delete($deleteId);
        Flash::success('Product deleted.');
        redirect('admin/products.php');
    }

    $input = ProductInput::fromArray($_POST);
    $errors = $input->errors();
    try {
        $image = UploadedImage::fromRequest('image', 10 * 1024 * 1024);
        if ($image === null) {
            $errors[] = 'Please upload a product image.';
        }
    } catch (UploadException $e) {
        $image = null;
        $errors[] = $e->getMessage();
    }

    if ($errors === [] && $image !== null) {
        $products->create($input, $image);
        Flash::success(sprintf('Product "%s" added.', $input->name));
    } else {
        Flash::error(implode(' ', $errors));
    }
    redirect('admin/products.php');
}

$list = $products->latest();
$categories = $products->categories();

$title = 'Products';
require dirname(__DIR__) . '/includes/layout/admin-start.php';
?>

<h1 class="text-2xl font-semibold tracking-tight">Products</h1>
<p class="mt-1 text-sm text-neutral-500">Products shown on the Explore page.</p>

<div class="mt-8 grid gap-6 xl:grid-cols-[1fr_22rem]">
    <div class="card overflow-x-auto">
        <table class="min-w-full text-left text-sm">
            <thead class="border-b border-black/5 text-xs uppercase tracking-wider text-neutral-500">
                <tr>
                    <th class="px-6 py-3 font-medium">Product</th>
                    <th class="px-6 py-3 font-medium">Category</th>
                    <th class="px-6 py-3 font-medium">Price</th>
                    <th class="px-6 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-black/5">
                <?php foreach ($list as $product): ?>
                    <tr class="hover:bg-white/50">
                        <td class="px-6 py-3">
                            <a href="<?= url('product.php?id=' . $product->id) ?>" target="_blank" class="flex items-center gap-4">
                                <?php if (($src = blob_src($product->image)) !== null): ?>
                                    <img src="<?= $src ?>" alt="" class="h-12 w-12 rounded-lg object-cover">
                                <?php else: ?>
                                    <span class="h-12 w-12 rounded-lg bg-black/5"></span>
                                <?php endif; ?>
                                <span class="font-medium hover:underline"><?= e($product->name) ?></span>
                            </a>
                        </td>
                        <td class="px-6 py-3 text-neutral-600"><?= e($product->category) ?></td>
                        <td class="whitespace-nowrap px-6 py-3"><?= format_price($product->price) ?></td>
                        <td class="px-6 py-3 text-right">
                            <form method="POST" onsubmit="return confirm('Delete this product?')">
                                <?= csrf_field() ?>
                                <input type="hidden" name="delete_id" value="<?= $product->id ?>">
                                <button class="btn btn-ghost btn-sm text-red-600 hover:bg-red-50 hover:text-red-700">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if ($list === []): ?>
                    <tr><td colspan="4" class="px-6 py-10 text-center text-neutral-500">No products yet.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <section class="card self-start p-6">
        <h2 class="font-semibold">Add product</h2>
        <form method="POST" enctype="multipart/form-data" class="mt-5 space-y-4">
            <?= csrf_field() ?>
            <div>
                <label for="name" class="label">Name</label>
                <input id="name" name="name" required maxlength="255" class="input">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="category" class="label">Category</label>
                    <input id="category" name="category" list="categories" required maxlength="50" class="input">
                    <datalist id="categories">
                        <?php foreach ($categories as $category): ?><option value="<?= e($category) ?>"><?php endforeach; ?>
                    </datalist>
                </div>
                <div>
                    <label for="price" class="label">Price ($)</label>
                    <input type="number" id="price" name="price" min="0.01" step="0.01" required class="input">
                </div>
            </div>
            <div>
                <label for="image" class="label">Image</label>
                <input type="file" id="image" name="image" accept="image/*" required
                       class="block w-full text-sm text-neutral-500 file:mr-3 file:rounded-full file:border-0 file:bg-black/5 file:px-4 file:py-2 file:text-sm file:font-medium file:text-neutral-900 hover:file:bg-neutral-200">
            </div>
            <div>
                <label for="description" class="label">Description</label>
                <textarea id="description" name="description" rows="4" class="input"></textarea>
            </div>
            <button class="btn btn-primary w-full">Add product</button>
        </form>
    </section>
</div>

<?php require dirname(__DIR__) . '/includes/layout/admin-end.php'; ?>
