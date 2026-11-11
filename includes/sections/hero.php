<?php declare(strict_types=1); ?>
<!--
    Hero. While <html> has the "lights-off" class the whole site is dark:
    a fixed overlay covers every section except the navbar and this lamp.
    Clicking the lamp removes the class and the site fades to light.
-->
<div class="pointer-events-none fixed inset-0 z-40 bg-neutral-950/95 opacity-0 transition-opacity duration-1000 dark:pointer-events-auto dark:opacity-100" aria-hidden="true"></div>

<section class="relative">
    <!-- Warm glow that appears once the light is on -->
    <div class="pointer-events-none absolute inset-x-0 top-0 h-[80vh] bg-[radial-gradient(ellipse_at_center,rgb(255_210_140/0.45),transparent_65%)] transition-opacity duration-1000 dark:opacity-0"></div>

    <div class="container-page relative z-[45] flex min-h-[100svh] items-center justify-center pb-12 pt-28">
        <div class="w-full max-w-4xl">
            <div class="glass-dark flex aspect-[16/9] w-full flex-col items-center justify-center gap-6 rounded-[2.5rem] transition-opacity duration-500" data-hero-dark>
                <button type="button" class="group flex flex-col items-center gap-5 text-white focus:outline-none" data-hero-switch aria-label="Turn the light on">
                    <span class="relative block h-24 w-24 transition duration-300 group-hover:scale-110 group-focus-visible:scale-110 sm:h-32 sm:w-32">
                        <span class="absolute inset-0 rounded-full bg-amber-200/0 blur-2xl transition duration-500 group-hover:bg-amber-200/40"></span>
                        <img src="<?= url('assets/images/lamp-off.svg') ?>" alt="" class="absolute inset-0 h-full w-full transition-opacity group-hover:opacity-0">
                        <img src="<?= url('assets/images/lamp-on.svg') ?>" alt="" class="absolute inset-0 h-full w-full opacity-0 transition-opacity group-hover:opacity-100">
                    </span>
                    <span class="text-base font-extralight tracking-wide text-white/90 sm:text-lg">Turn your light up</span>
                </button>
            </div>
            <img src="<?= url('assets/images/hero-lit.svg') ?>" alt="A workspace lit by SES smart lighting"
                 class="hidden w-full opacity-0 drop-shadow-2xl transition-opacity duration-700" data-hero-lit>
        </div>
    </div>
</section>

<script>
    (() => {
        const root = document.documentElement;
        const dark = document.querySelector('[data-hero-dark]');
        const lit = document.querySelector('[data-hero-lit]');

        const showLit = () => {
            dark.classList.add('hidden');
            lit.classList.remove('hidden');
            requestAnimationFrame(() => lit.classList.remove('opacity-0'));
        };

        // The page always starts dark; the light only comes on when the lamp is clicked.
        document.querySelector('[data-hero-switch]').addEventListener('click', () => {
            root.classList.remove('lights-off');
            dark.classList.add('opacity-0');
            setTimeout(showLit, 500);
        });
    })();
</script>
