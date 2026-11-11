<?php

declare(strict_types=1);

$stats = [
    ['value' => '25%', 'label' => 'More Efficient', 'text' => 'Our smart lamps use far less energy than traditional lighting, cutting costs while maximizing brightness and longevity.'],
    ['value' => '0', 'label' => 'Carbon Impact', 'text' => 'Designed with sustainability in mind, our technology helps you work smarter without harming the environment.'],
    ['value' => '100%', 'label' => 'Adaptive Lighting', 'text' => 'Automatically adjusts brightness and color to your work mode, reducing eye strain and boosting focus all day.'],
];
?>
<!-- Key numbers over an office photo -->
<section class="relative overflow-hidden">
    <img src="<?= url('assets/images/office-interior.png') ?>" alt="" class="absolute inset-0 h-full w-full object-cover">
    <div class="absolute inset-0 bg-white/10"></div>

    <div class="container-page relative py-20 lg:py-28">
        <div class="grid gap-4 md:grid-cols-3 lg:max-w-5xl">
            <?php foreach ($stats as $stat): ?>
                <div class="flex min-h-[18rem] flex-col glass rounded-[2rem] p-8">
                    <p class="text-6xl font-bold tracking-tight"><?= $stat['value'] ?></p>
                    <p class="mt-2 text-xl"><?= $stat['label'] ?></p>
                    <p class="mt-auto pt-8 text-sm leading-relaxed text-neutral-800"><?= $stat['text'] ?></p>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="glass mt-16 max-w-md rounded-[2rem] p-8 lg:ml-auto lg:mt-24">
            <p class="text-sm leading-relaxed text-neutral-700">
                Our lighting solutions deliver more than just light. They create healthier, productive spaces while prioritizing the planet.
            </p>
            <h2 class="mt-4 text-4xl font-light leading-tight tracking-tight">
                Smarter Light, Better Workspaces, Greener Future.
            </h2>
        </div>
    </div>
</section>
