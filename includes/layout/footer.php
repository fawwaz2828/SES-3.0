<?php

declare(strict_types=1);

/**
 * Page end for the public site and the user area.
 *
 * @var bool|null $showFooter defaults to true
 */
?>
</main>

<?php if ($showFooter ?? true): ?>
<footer class="mx-3 mb-3 mt-24 overflow-hidden rounded-[2.5rem] bg-neutral-950 text-neutral-300">
    <div class="container-page grid gap-12 py-16 md:grid-cols-2">
        <div class="max-w-sm space-y-5">
            <img src="<?= url('assets/images/logo-white.svg') ?>" alt="SES" class="h-7 w-auto">
            <p class="text-sm leading-relaxed text-neutral-400">
                At SES, we bring artistry and innovation together to illuminate your spaces. Smart lighting
                tailored to your needs makes your life brighter, smarter, and more efficient.
            </p>
        </div>

        <div class="md:justify-self-end">
            <h3 class="text-base font-medium text-white">Get notified by our newsletter</h3>
            <form class="mt-4 flex max-w-md flex-col gap-2 sm:flex-row" data-newsletter>
                <input type="email" required placeholder="you@company.com"
                       class="flex-1 rounded-full border border-neutral-700 bg-neutral-900 px-4 py-2.5 text-sm text-white placeholder:text-neutral-500 focus:border-white focus:outline-none">
                <button class="btn bg-white text-neutral-900 hover:bg-neutral-200">Subscribe</button>
            </form>
            <p class="mt-2 hidden text-sm text-emerald-400" data-newsletter-done>Thanks for subscribing!</p>

            <div class="mt-8 flex gap-6 text-sm">
                <a href="<?= url('explore.php') ?>" class="hover:text-white">Explore</a>
                <a href="<?= url('blog.php') ?>" class="hover:text-white">Blog</a>
                <a href="<?= url('consultation-form.php') ?>" class="hover:text-white">Consultation</a>
            </div>
        </div>
    </div>

    <div class="container-page overflow-hidden pb-6">
        <p class="select-none text-center text-[11vw] font-extrabold leading-none tracking-tighter text-neutral-800 xl:text-[140px]">
            Sustain Energy Solution
        </p>
        <p class="mt-6 text-center text-xs text-neutral-500">&copy; <?= date('Y') ?> SES. All rights reserved.</p>
    </div>
</footer>

<script>
    document.querySelector('[data-newsletter]').addEventListener('submit', (event) => {
        event.preventDefault();
        event.target.reset();
        document.querySelector('[data-newsletter-done]').classList.remove('hidden');
    });
</script>
<?php endif; ?>
</body>
</html>
