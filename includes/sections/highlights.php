<?php declare(strict_types=1); ?>
<!-- Smart lighting highlights -->
<section class="container-page grid gap-10 py-24 lg:grid-cols-12 lg:items-center">
    <div class="space-y-6 lg:col-span-5">
        <h2 class="text-4xl font-semibold leading-tight tracking-tight">
            Smarter Light, Better Workspaces, Greener Future.
        </h2>
        <p class="text-lg leading-relaxed text-neutral-600">
            Take control of your lighting with smart technology that lets you adjust brightness, color, and ambiance
            with simple voice or app commands. Energy-efficient solutions that blend function with stunning design,
            tailored to your lifestyle.
        </p>
    </div>

    <div class="grid grid-cols-2 gap-4 lg:col-span-7 lg:grid-cols-3">
        <?php
        $cards = [
            ['img' => 'site-survey.png', 'label' => 'Free Site Survey', 'class' => 'aspect-[4/3] lg:col-span-2'],
            ['img' => 'energy-artistry.png', 'label' => 'Energy Efficiency Meets Artistry', 'class' => 'row-span-2 hidden lg:block'],
            ['img' => 'control-light.png', 'label' => 'Control Your Light', 'class' => 'aspect-[4/3] lg:col-span-2'],
        ];
        foreach ($cards as $card): ?>
            <figure class="relative overflow-hidden rounded-[2rem] <?= $card['class'] ?>">
                <img src="<?= url('assets/images/' . $card['img']) ?>" alt="<?= $card['label'] ?>" loading="lazy"
                     class="h-full w-full object-cover">
                <figcaption class="glass absolute bottom-4 left-4 rounded-full px-4 py-1.5 text-sm font-medium">
                    <?= $card['label'] ?>
                </figcaption>
            </figure>
        <?php endforeach; ?>
    </div>
</section>
