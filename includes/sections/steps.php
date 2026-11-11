<?php

declare(strict_types=1);

$steps = [
    ['title' => 'Book a Consultation', 'text' => 'Take the first step by booking a consultation with our team, so we can schedule a convenient time to evaluate your lighting needs.'],
    ['title' => 'Site Survey', 'text' => 'Our experts visit your location to understand your requirements, assess the space, and recommend the ideal lighting solution.'],
    ['title' => 'Sign Off', 'text' => 'Review the proposed plan and confirm the details. Once approved, we move forward to bring your lighting vision to life.'],
    ['title' => 'Get Your Light', 'text' => 'Enjoy a seamless experience as we deliver and install your lighting, transforming your space effortlessly.'],
];
?>
<!-- How it works -->
<section class="py-24">
    <div class="container-page grid gap-12 lg:grid-cols-[20rem_1fr] lg:items-end lg:gap-20">
        <div class="space-y-6">
            <h2 class="text-4xl font-light leading-tight tracking-tight">
                Embrace Future Light To Your Workspace
                <span class="block text-neutral-400">With Four Simple Steps</span>
            </h2>
            <img src="<?= url('assets/images/steps.png') ?>" alt="Hand holding a light bulb against the sunset"
                 class="w-full rounded-2xl object-cover" loading="lazy">
        </div>

        <ol class="grid gap-4 md:grid-cols-2">
            <?php foreach ($steps as $i => $step): ?>
                <li class="card flex flex-col gap-6 p-8 transition duration-300 hover:-translate-y-1 hover:shadow-lg">
                    <div class="flex items-center gap-4">
                        <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-neutral-900 text-lg text-white"><?= $i + 1 ?></span>
                        <h3 class="text-xl"><?= $step['title'] ?></h3>
                    </div>
                    <p class="leading-relaxed text-neutral-600"><?= $step['text'] ?></p>
                </li>
            <?php endforeach; ?>
        </ol>
    </div>
</section>
