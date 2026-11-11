<?php

declare(strict_types=1);

$testimonials = [
    ['text' => "I love how effortlessly I can control my lights from my phone. It's not just efficient but also adds a touch of modern elegance to my space.", 'author' => 'Sarah Johnson'],
    ['text' => 'The lighting system is incredible! It transformed my workspace into a productive and comfortable environment.', 'author' => 'John Doe'],
    ['text' => 'Great experience! The installation was quick, and now my office looks amazing with these smart lights.', 'author' => 'Emily Carter'],
];
?>
<!-- Testimonials -->
<section class="container-page py-12">
    <div class="relative overflow-hidden rounded-[2rem]">
        <img src="<?= url('assets/images/testimonial-bg.png') ?>" alt="" class="absolute inset-0 h-full w-full object-cover" loading="lazy">
        <div class="absolute inset-0 bg-black/30"></div>

        <div class="relative flex min-h-[36rem] flex-col justify-between gap-12 p-8 sm:p-12 lg:min-h-[42rem]">
            <h2 class="max-w-xl text-4xl font-medium tracking-tight text-white md:text-6xl">
                A better future starts from your workspace
            </h2>

            <div class="glass w-full max-w-xl self-end rounded-[2rem] p-6" data-testimonials='<?= e(json_encode($testimonials, JSON_THROW_ON_ERROR)) ?>'>
                <p class="text-sm font-medium text-neutral-500">Who have gotten their light with us</p>
                <blockquote class="mt-4 min-h-[5rem] text-lg leading-relaxed" data-text><?= e($testimonials[0]['text']) ?></blockquote>
                <div class="mt-6 flex items-center justify-between">
                    <p class="text-sm font-semibold" data-author><?= e($testimonials[0]['author']) ?></p>
                    <div class="flex gap-2">
                        <button type="button" class="btn btn-outline h-9 w-9 p-0" data-step="-1" aria-label="Previous testimonial">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m15 18-6-6 6-6"/></svg>
                        </button>
                        <button type="button" class="btn btn-outline h-9 w-9 p-0" data-step="1" aria-label="Next testimonial">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m9 18 6-6-6-6"/></svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    (() => {
        const box = document.querySelector('[data-testimonials]');
        const items = JSON.parse(box.dataset.testimonials);
        let index = 0;
        box.querySelectorAll('[data-step]').forEach((button) => {
            button.addEventListener('click', () => {
                index = (index + Number(button.dataset.step) + items.length) % items.length;
                box.querySelector('[data-text]').textContent = items[index].text;
                box.querySelector('[data-author]').textContent = items[index].author;
            });
        });
    })();
</script>
